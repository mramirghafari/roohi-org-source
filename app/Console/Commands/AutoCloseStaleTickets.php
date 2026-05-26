<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Services\TicketNotificationService;
use Illuminate\Console\Command;

class AutoCloseStaleTickets extends Command
{
    protected $signature = 'tickets:auto-close-stale';

    protected $description = 'Auto close non-closed tickets that were not followed up for 7 days';

    public function handle(TicketNotificationService $ticketNotificationService): int
    {
        $closedCount = 0;

        Ticket::query()
            ->where('status', '!=', Ticket::STATUS_CLOSED)
            ->where(function ($query) {
                $query->whereNotNull('last_reply_at')
                    ->where('last_reply_at', '<=', now()->subDays(7))
                    ->orWhere(function ($q) {
                        $q->whereNull('last_reply_at')
                            ->where('created_at', '<=', now()->subDays(7));
                    });
            })
            ->chunkById(100, function ($tickets) use (&$closedCount, $ticketNotificationService) {
                foreach ($tickets as $ticket) {
                    $ticket->update([
                        'status' => Ticket::STATUS_CLOSED,
                        'closed_at' => now(),
                    ]);

                    $ticketNotificationService->notifyAutoClosed($ticket);
                    $closedCount++;
                }
            });

        $this->info("{$closedCount} stale ticket(s) were closed.");

        return self::SUCCESS;
    }
}
