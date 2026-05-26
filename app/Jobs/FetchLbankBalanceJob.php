<?php

namespace App\Jobs;

use App\Models\Bot2; // 👈 مدل Bot2 (برای User)
use App\Models\UserBalanceLog; // 👈 مدل لاگ
use App\Services\LbankApiService; // 👈 سرویس Lbank
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable; // 👈 Trait ضروری
use Illuminate\Queue\InteractsWithQueue; // 👈 Trait ضروری
use Illuminate\Queue\Middleware\RateLimited; // 👈 کلاس RateLimited
use Illuminate\Queue\SerializesModels; // 👈 Trait ضروری

class FetchLbankBalanceJob implements ShouldQueue
{
    // استفاده از تمامی Traits های استاندارد Job
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // اگر Job برای Rate Limit کردن منتظر شد، برای اجرای مجدد (Retry) تا 3 بار تلاش میکند.
    public int $tries = 3;

    // User ID را از بیرون دریافت و در Job نگهداری میکنیم
    public function __construct(
        public int $userId // فرض: user_id را از جدول Bot2 میگیریم.
    ) {}

    /**
     * اجرای اصلی Job.
     * @param LbankApiService $lbank (Dependency Injection)
     */
    public function handle(LbankApiService $lbank)
    {
        // 1. یافتن کاربر
        // فرض می‌کنیم در مدل Bot2، کلید اصلی (Primary Key) همان id است.
        $user = Bot2::find($this->userId);

        if (!$user || empty($user->lbank_uid)) {
            // اگر کاربر پیدا نشد یا lbank_uid نداشت، Job را متوقف میکنیم
            return;
        }

        // 2. کال کردن سرویس LBank
        // فرض می‌کنیم تابع getLbankBalance به درستی پیاده‌سازی شده
        $balance = $lbank->getLbankBalance($user->lbank_uid);

        // 3. ثبت لاگ موجودی
        UserBalanceLog::create([
            'user_id'   => $user->user_id,
            'lbank_uid' => $user->lbank_uid,
            'deposit'   => $balance, // 'deposit' در اینجا مفهوم current_balance دارد
            'time'      => now(),
        ]);
    }

    /**
     * تعریف Rate Limiter برای این Job
     */
    public function middleware(): array
    {
        return [
            // استفاده از Throttler که در AppServiceProvider تعریف کردیم
            new RateLimited('lbank-api'),
        ];
    }
}
