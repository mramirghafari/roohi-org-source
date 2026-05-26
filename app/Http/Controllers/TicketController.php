<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketMessage;
use App\Models\User;
use App\Services\TicketNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::query()
            ->where('user_id', auth()->id())
            ->with(['assignee:id,nam,name,last_name', 'messages'])
            ->orderByDesc('updated_at')
            ->paginate(20);

        return view('dashboard.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $departments = Ticket::DEPARTMENTS;
        $priorities = Ticket::PRIORITIES;

        return view('dashboard.tickets.create', compact('departments', 'priorities'));
    }

    public function store(Request $request, TicketNotificationService $ticketNotificationService)
    {
        $validated = $request->validate([
            'department' => 'required|in:' . implode(',', array_keys(Ticket::DEPARTMENTS)),
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:' . implode(',', array_keys(Ticket::PRIORITIES)),
            'message' => 'required|string|max:10000',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,bmp,pdf,zip|max:10240',
        ]);

        $ticket = null;
        $ticketMessage = null;

        DB::transaction(function () use ($request, $validated, &$ticket, &$ticketMessage) {
            $ticket = Ticket::create([
                'user_id' => auth()->id(),
                'department' => $validated['department'],
                'subject' => $validated['subject'],
                'tracking_code' => $this->generateTrackingCode(),
                'priority' => $validated['priority'],
                'status' => Ticket::STATUS_OPEN,
                'assigned_to' => $this->findAssigneeId($validated['department']),
                'last_reply_by' => auth()->id(),
                'last_reply_at' => now(),
            ]);

            $ticketMessage = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $validated['message'],
            ]);

            $this->storeAttachments($request, $ticket, $ticketMessage);
        });

        $ticketNotificationService->notifyCreated($ticket);

        return redirect()->route('tickets.show', $ticket->tracking_code)->with('success', 'تیکت شما با موفقیت ثبت شد. کد پیگیری: ' . $ticket->tracking_code);
    }

    public function show(string $tracking_code)
    {
        $ticket = Ticket::query()
            ->where('tracking_code', $tracking_code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $ticket->load([
            'messages.user:id,nam,name,last_name,is_support,isAdmin',
            'messages.attachments',
            'assignee:id,nam,name,last_name',
        ]);

        return view('dashboard.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, string $tracking_code)
    {
        $ticket = Ticket::query()
            ->where('tracking_code', $tracking_code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($ticket->status === Ticket::STATUS_CLOSED) {
            return redirect()->back()->with('error', 'این تیکت بسته شده و امکان پاسخ وجود ندارد.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:10000',
            'attachments' => 'nullable|array|max:3',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,bmp,pdf,zip|max:10240',
        ]);

        DB::transaction(function () use ($request, $validated, $ticket) {
            $ticketMessage = TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'message' => $validated['message'],
            ]);

            $this->storeAttachments($request, $ticket, $ticketMessage);

            $ticket->update([
                'status' => Ticket::STATUS_ANSWERED_BY_USER,
                'last_reply_by' => auth()->id(),
                'last_reply_at' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'پاسخ شما ثبت شد.');
    }

    public function close(string $tracking_code, TicketNotificationService $ticketNotificationService)
    {
        $ticket = Ticket::query()
            ->where('tracking_code', $tracking_code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($ticket->status === Ticket::STATUS_CLOSED) {
            return redirect()->back()->with('error', 'این تیکت قبلا بسته شده است.');
        }

        $ticket->update([
            'status' => Ticket::STATUS_CLOSED,
            'closed_at' => now(),
        ]);

        $ticketNotificationService->notifyClosedByUser($ticket);

        return redirect()->back()->with('success', 'تیکت با موفقیت بسته شد.');
    }

    public function downloadAttachment(TicketAttachment $attachment)
    {
        $ticket = $attachment->ticket;
        abort_unless($ticket, 404);

        $user = auth()->user();
        $isTicketOwner = (int) $ticket->user_id === (int) $user->id;
        $isSupportOrAdmin = (int) $user->is_support === 1 || (int) $user->isAdmin === 1;

        abort_unless($isTicketOwner || $isSupportOrAdmin, 403);

        abort_unless(Storage::disk('local')->exists($attachment->path), 404);

        return Storage::disk('local')->download($attachment->path, $attachment->original_name);
    }

    private function storeAttachments(Request $request, Ticket $ticket, TicketMessage $ticketMessage): void
    {
        if (!$request->hasFile('attachments')) {
            return;
        }

        foreach ($request->file('attachments') as $file) {
            $path = $file->store('ticket-attachments', 'local');

            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'ticket_message_id' => $ticketMessage->id,
                'user_id' => auth()->id(),
                'original_name' => $file->getClientOriginalName(),
                'stored_name' => basename($path),
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }

    private function findAssigneeId(string $department): ?int
    {
        $supportUser = User::query()
            ->where('is_support', 1)
            ->whereHas('supportDepartments', function ($query) use ($department) {
                $query->where('department', $department);
            })
            ->withCount(['assignedTickets as active_tickets_count' => function ($query) {
                $query->whereIn('status', [
                    Ticket::STATUS_OPEN,
                    Ticket::STATUS_ANSWERED_BY_USER,
                    Ticket::STATUS_ANSWERED_BY_SUPPORT,
                ]);
            }])
            ->orderBy('active_tickets_count')
            ->orderBy('id')
            ->first();

        if ($supportUser) {
            return (int) $supportUser->id;
        }

        $fallback = User::query()
            ->where('is_support', 1)
            ->withCount(['assignedTickets as active_tickets_count' => function ($query) {
                $query->whereIn('status', [
                    Ticket::STATUS_OPEN,
                    Ticket::STATUS_ANSWERED_BY_USER,
                    Ticket::STATUS_ANSWERED_BY_SUPPORT,
                ]);
            }])
            ->orderBy('active_tickets_count')
            ->orderBy('id')
            ->first();

        return $fallback ? (int) $fallback->id : null;
    }

    private function generateTrackingCode(): string
    {
        do {
            $code = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (Ticket::query()->where('tracking_code', $code)->exists());

        return $code;
    }
}
