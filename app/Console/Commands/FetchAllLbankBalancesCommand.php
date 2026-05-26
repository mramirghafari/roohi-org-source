<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bot2; // 👈 مدل کاربر شما
use App\Jobs\FetchLbankBalanceJob; // 👈 Job ای که ساختیم

class FetchAllLbankBalancesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lbank:fetch-all-balances'; // 👈 نام Command

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches current LBank balances for all active users and logs them.'; // 👈 توضیحات Command

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to dispatch LBank balance fetch jobs for all active users...');

        // 1. دریافت همه کاربران فعال که lbank_uid دارند
        // فرض می‌کنیم Bot2 مدل کاربران شماست
        // و user_id کلید اصلی یا کلیدی است که در Job استفاده می‌کنید.
        // whereNotNull('lbank_uid') برای اطمینان از وجود lbank_uid
        // status 1 (یا هر فیلد دیگری که نشان‌دهنده کاربر فعال است)

        $users = Bot2::whereNotNull('lbank_uid')
                     // ->where('status', 1) // 👈 اگر فیلد 'status' برای کاربران فعال دارید
                     ->select('id') // فقط id را انتخاب می‌کنیم برای بهینه‌سازی
                     ->cursor(); // استفاده از cursor برای حافظه بهینه در تعداد زیاد کاربر

        $dispatchedCount = 0;
        foreach ($users as $user) {
            // برای هر کاربر یک Job را به صف ارسال می‌کنیم
            FetchLbankBalanceJob::dispatch($user->id); // فرض: id از جدول Bot2 همان userId در Job است
            $dispatchedCount++;
        }

        $this->info("Dispatched {$dispatchedCount} LBank balance fetch jobs to the queue.");
        $this->info('Please ensure your queue worker is running to process these jobs.');
    }
}
