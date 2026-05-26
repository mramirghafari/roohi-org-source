<?php

namespace App\Services;

use App\Models\Notifs;
use App\Models\Ticket;

class TicketNotificationService
{
    public function notifyCreated(Ticket $ticket): void
    {
        $department = Ticket::DEPARTMENTS[$ticket->department] ?? $ticket->department;

        Notifs::create([
            'user_id' => $ticket->user_id,
            'title' => 'تیکت جدید شما با موضوع ' . $ticket->subject . ' ایجاد شد',
            'content' => 'کاربر گرامی، تیکت جدید شما با عنوان: ' . $ticket->subject . ' برای واحد ' . $department . ' ایجاد گردید' . "\n" . $this->ticketLinkText($ticket),
            'status' => 0,
            'sms' => 0,
        ]);
    }

    public function notifySupportReply(Ticket $ticket): void
    {
        $department = Ticket::DEPARTMENTS[$ticket->department] ?? $ticket->department;

        Notifs::create([
            'user_id' => $ticket->user_id,
            'title' => 'پاسخ جدید برای تیکت ' . $ticket->subject,
            'content' => 'کاربر گرامی تیکت شما با موضوع ' . $ticket->subject . ' توسط تیم ' . $department . ' پاسخ داده شده' . "\n" . $this->ticketLinkText($ticket),
            'status' => 0,
            'sms' => 0,
        ]);
    }

    public function notifyClosedByUser(Ticket $ticket): void
    {
        Notifs::create([
            'user_id' => $ticket->user_id,
            'title' => 'تیکت ' . $ticket->subject . ' بسته شد',
            'content' => 'کاربر گرامی تیکت شما با موضوع ' . $ticket->subject . ' توسط خود شما بسته شد' . "\n" . $this->ticketLinkText($ticket),
            'status' => 0,
            'sms' => 0,
        ]);
    }

    public function notifyClosedBySupport(Ticket $ticket): void
    {
        $department = Ticket::DEPARTMENTS[$ticket->department] ?? $ticket->department;

        Notifs::create([
            'user_id' => $ticket->user_id,
            'title' => 'تیکت ' . $ticket->subject . ' بسته شد',
            'content' => 'کاربر گرامی تیکت شما با موضوع ' . $ticket->subject . ' توسط تیم ' . $department . ' بسته شد' . "\n" . $this->ticketLinkText($ticket),
            'status' => 0,
            'sms' => 0,
        ]);
    }

    public function notifyAutoClosed(Ticket $ticket): void
    {
        Notifs::create([
            'user_id' => $ticket->user_id,
            'title' => 'تیکت ' . $ticket->subject . ' به صورت خودکار بسته شد',
            'content' => 'کاربر گرامی تیکت شما با موضوع ' . $ticket->subject . ' به دلیل عدم پیگیری در 7 روز گذشته بسته شد' . "\n" . $this->ticketLinkText($ticket),
            'status' => 0,
            'sms' => 0,
        ]);
    }

    private function ticketLinkText(Ticket $ticket): string
    {
        $url = url('/ticket/' . $ticket->tracking_code);

        return 'برای مشاهده تیکت با شماره پیگیری ' . $ticket->tracking_code . ' اینجا کلیک کنید: ' . $url;
    }
}
