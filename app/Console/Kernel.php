<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ... (سایر زمان‌بندی‌ها اگر وجود دارد)

        // هر روز در ساعت 03:00 بامداد (مثلاً) اجرا شود
       /* $schedule->command('lbank:fetch-all-balances')
                 ->dailyAt('03:00') // 👈 زمان اجرا را اینجا مشخص کنید
                 ->onOneServer() // 👈 اگر چندین سرور دارید، فقط روی یک سرور اجرا شود (مهم برای جلوگیری از اجرای تکراری)
                  ->withoutOverlapping() // 👈 (اختیاری) اگر اجرای قبلی هنوز در حال اجرا بود، اجرای جدید را نادیده بگیرد
                  ->runInBackground(); // 👈 (اختیاری) Command را در پس‌زمینه اجرا کند (برای Commandهای طولانی) */

        //$schedule->command('remote:cleanup')->everyMinute();


        // هر دقیقه 24 نفر = 4 request در 10 ثانیه (ایمن)
        $schedule->command('lbank:balance:poll')
            ->everyMinute()
            ->withoutOverlapping();

        // Sync صف
        $schedule->command('lbank:queue:sync')->hourly();
        
        $schedule->command('signal:sms-batch --limit=1000')
        ->everyMinute()
        ->withoutOverlapping();

        // ارسال نوتیف‌های آرشیو شده هر دقیقه
        $schedule->command('notifications:send-archived')
            ->everyMinute()
            ->withoutOverlapping();

        // بستن خودکار تیکت‌های بدون پیگیری بیش از 7 روز
        $schedule->command('tickets:auto-close-stale')
            ->dailyAt('02:30')
            ->withoutOverlapping();


        // Fallback: اگر به هر دلیلی بالا ثبت نشد، مستقیم Artisan call را زمان‌بندی کن
        $schedule->call(function () {
            try {
                \Artisan::call('notifications:send-archived');
            } catch (\Throwable $e) {
                Log::error('Fallback notifications scheduler failed: ' . $e->getMessage());
            }
        })->everyMinute()->withoutOverlapping()->name('notifications:send-archived-call');

        // debug
        try {
            Log::info('Scheduler loaded, total events: ' . count($schedule->events()));
            foreach ($schedule->events() as $ev) {
                Log::debug('Scheduled command: ' . ($ev->getSummaryForDisplay() ?? 'unknown'));
            }
        } catch (\Throwable $e) {
            Log::error('Scheduler debug failed: ' . $e->getMessage());
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
