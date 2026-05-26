<?php

namespace App\Services;

use App\Models\User;
use App\Models\ArchiveNotif;
use App\Models\Notifs;
use App\Jobs\SendSmsNotificationJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * ارسال نوتیف‌های آرشیو شده به کاربران
     * 
     * usersGroup values:
     * 1 = تمام کاربران
     * 2 = کاربران VIP فعال
     * 3 = کاربران بدون VIP
     * 8 = کاربران احراز نشده (پروفایل ناقص: nam، birthdate، gender خالی)
     * 9 = کاربرانی بدون LBank UID
     * 10 = کاربرانی بدون CoincAll UID
     * 11 = کاربرانی با موجودی LBank کمتر از 50 دلار
     * 12 = (رزرو شده برای بعدا)
     */
    public function sendArchivedNotifications()
    {
        // نوتیف‌های ارسال نشده را پیدا کن
        $archivedNotifs = ArchiveNotif::where('status', 0)
            ->orWhereNull('status')
            ->get();

        foreach ($archivedNotifs as $archive) {
            $users = $this->getUsersByGroup($archive->usersGroup);

            foreach ($users as $user) {
                // چک کن که نوتیف قبلاً برای این کاربر ارسال نشده
                $exists = Notifs::where('user_id', $user->id)
                    ->where('archive_notif_id', $archive->id)
                    ->exists();

                if (!$exists) {
                    try {
                        $SendedNotif = Notifs::create([
                            'user_id' => $user->id,
                            'archive_notif_id' => $archive->id,
                            'title' => $archive->title,
                            'content' => $archive->content,
                            'status' => 0, // 0 = نخوانده شده
                            'sms' => $archive->sms ? 1 : 0,
                        ]);

                        // اگر SMS فعال باشد، اضافه کن به صف
                        if ($archive->sms && $user->mobile) {
                            SendSmsNotificationJob::dispatch($SendedNotif->id);
                        }
                    } catch (\Exception $e) {
                        Log::error("Error creating notification for user {$user->id}: " . $e->getMessage());
                    }
                }
            }

            // علامت بزن که نوتیف ارسال شد
            $archive->update(['status' => 1]);
        }

        return true;
    }

    /**
     * دریافت کاربران بر اساس گروه
     */
    private function getUsersByGroup($groupId)
    {
        switch ($groupId) {
            case 1:
                // تمام کاربران
                return User::all();

            case 2:
                // کاربران VIP فعال
                return User::withActiveVip()->get();

            case 3:
                // کاربران با اشتراک کمتر از 10 روز
                return User::whereHas('subscribes', function($q) {
                    $q->where('status', 1)
                      ->where('exp_vip', '>=', now())
                      ->where('exp_vip', '<=', now()->addDays(10));
                })->get();

            case 4:
                // کاربران با اشتراک کمتر از 7 روز
                return User::whereHas('subscribes', function($q) {
                    $q->where('status', 1)
                      ->where('exp_vip', '>=', now())
                      ->where('exp_vip', '<=', now()->addDays(7));
                })->get();

            case 5:
                // کاربران با اشتراک کمتر از 3 روز
                return User::whereHas('subscribes', function($q) {
                    $q->where('status', 1)
                      ->where('exp_vip', '>=', now())
                      ->where('exp_vip', '<=', now()->addDays(3));
                })->get();

            case 6:
                // کاربران با اشتراک کمتر از 2 روز
                return User::whereHas('subscribes', function($q) {
                    $q->where('status', 1)
                      ->where('exp_vip', '>=', now())
                      ->where('exp_vip', '<=', now()->addDays(2));
                })->get();

            case 7:
                // کاربران رفته (بدون VIP فعال)
                return User::withExpiredVip()->get();

            case 8:
                // کاربران احراز نشده (پروفایل ناقص)
                // nam یا birthdate یا gender خالی باشند
                return User::where(function($q) {
                    $q->whereNull('nam')
                      ->orWhereNull('birthdate')
                      ->orWhereNull('gender')
                      ->orWhere('nam', '')
                      ->orWhere('birthdate', '')
                      ->orWhere('gender', '');
                })->get();

            case 9:
                // کاربرانی بدون LBank UID
                return User::whereNull('lbank_uid')
                    ->orWhere('lbank_uid', '')
                    ->get();

            case 10:
                // کاربرانی بدون CoincAll UID
                return User::whereNull('coincall_uid')
                    ->orWhere('coincall_uid', '')
                    ->get();

            case 11:
                // کاربرانی با موجودی LBank کمتر از 50 دلار
                return User::whereHas('latestBalanceLog', function($q) {
                    $q->where('balance', '<', 50);
                })->get();

            case 12:
                // (رزرو برای کاربرانی با موجودی کمتر از 50 دلار در کوین لوکالی - بعدا)
                return collect();

            default:
                return collect(); // خالی
        }
    }
}
