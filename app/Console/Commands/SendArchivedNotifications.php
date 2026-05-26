<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class SendArchivedNotifications extends Command
{
    protected $signature = 'notifications:send-archived';
    protected $description = 'ارسال نوتیف‌های آرشیو شده به کاربران';

    public function __construct(private NotificationService $notificationService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('شروع ارسال نوتیف‌های آرشیو شده...');

        try {
            $this->notificationService->sendArchivedNotifications();
            $this->info('نوتیف‌ها با موفقیت ارسال شدند.');
            return 0;
        } catch (\Exception $e) {
            $this->error('خطا: ' . $e->getMessage());
            return 1;
        }
    }
}
