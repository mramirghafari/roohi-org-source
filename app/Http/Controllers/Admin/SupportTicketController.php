<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketAttachment;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Services\TicketNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query()->with(['user:id,nam,name,last_name,mobile', 'assignee:id,nam,name,last_name']);

        if ((int) auth()->user()->isAdmin !== 1) {
            $departments = auth()->user()->supportDepartments()->pluck('department');

            $query->where(function ($q) use ($departments) {
                $q->where('assigned_to', auth()->id())
                    ->orWhereIn('department', $departments);
            });
        }

        if ($request->filled('status') && in_array($request->status, Ticket::STATUSES, true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department') && array_key_exists($request->department, Ticket::DEPARTMENTS)) {
            $query->where('department', $request->department);
        }

        $tickets = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();
        $departments = Ticket::DEPARTMENTS;
        $statuses = Ticket::STATUSES;

        return view('dashboard.tickets.support_index', compact('tickets', 'departments', 'statuses'));
    }

    public function show(Ticket $ticket)
    {
        abort_unless($this->canAccessTicket($ticket), 403);

        $ticket->load([
            'user:id,nam,name,last_name,mobile',
            'assignee:id,nam,name,last_name',
            'messages.user:id,nam,name,last_name,is_support,isAdmin',
            'messages.attachments',
        ]);

        $supportUsers = User::query()
            ->where('is_support', 1)
            ->whereHas('supportDepartments', function ($query) use ($ticket) {
                $query->where('department', $ticket->department);
            })
            ->get(['id', 'nam', 'name', 'last_name']);

        return view('dashboard.tickets.support_show', compact('ticket', 'supportUsers'));
    }

    public function reply(Request $request, Ticket $ticket, TicketNotificationService $ticketNotificationService)
    {
        abort_unless($this->canAccessTicket($ticket), 403);

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
                'assigned_to' => $ticket->assigned_to ?: auth()->id(),
                'status' => Ticket::STATUS_ANSWERED_BY_SUPPORT,
                'last_reply_by' => auth()->id(),
                'last_reply_at' => now(),
            ]);
        });

        $ticketNotificationService->notifySupportReply($ticket->fresh());

        return redirect()->back()->with('success', 'پاسخ پشتیبانی ثبت شد.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        abort_unless($this->canAccessTicket($ticket), 403);

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $assignee = User::findOrFail($validated['assigned_to']);

        if ((int) $assignee->is_support !== 1 && (int) $assignee->isAdmin !== 1) {
            return redirect()->back()->with('error', 'کاربر انتخاب‌شده دسترسی پشتیبانی ندارد.');
        }

        if ((int) $assignee->isAdmin !== 1) {
            $hasDepartment = $assignee->supportDepartments()
                ->where('department', $ticket->department)
                ->exists();

            if (!$hasDepartment) {
                return redirect()->back()->with('error', 'این کاربر برای دپارتمان تیکت انتخاب‌شده دسترسی ندارد.');
            }
        }

        $ticket->update([
            'assigned_to' => $assignee->id,
        ]);

        return redirect()->back()->with('success', 'تیکت با موفقیت اساین شد.');
    }

    public function close(Ticket $ticket, TicketNotificationService $ticketNotificationService)
    {
        abort_unless($this->canAccessTicket($ticket), 403);

        $ticket->update([
            'status' => Ticket::STATUS_CLOSED,
            'closed_at' => now(),
        ]);

        $ticketNotificationService->notifyClosedBySupport($ticket->fresh());

        return redirect()->back()->with('success', 'تیکت بسته شد.');
    }

    public function reopen(Ticket $ticket)
    {
        abort_unless($this->canAccessTicket($ticket), 403);

        $ticket->update([
            'status' => Ticket::STATUS_OPEN,
            'closed_at' => null,
        ]);

        return redirect()->back()->with('success', 'تیکت مجدد باز شد.');
    }

    private function canAccessTicket(Ticket $ticket): bool
    {
        $user = auth()->user();

        if ((int) $user->isAdmin === 1) {
            return true;
        }

        if ((int) $user->is_support !== 1) {
            return false;
        }

        if ((int) $ticket->assigned_to === (int) $user->id) {
            return true;
        }

        return $user->supportDepartments()->where('department', $ticket->department)->exists();
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
}
