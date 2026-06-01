<?php

namespace App\Services;

use App\Models\Subscribe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class SubscriptionActivationService
{
    public function activate(int $userId, int $planMonths, int $amount, ?string $trackingCode): int
    {
        $now = now();

        $activeSub = Subscribe::query()
            ->where('user_id', $userId)
            ->where('status', 1)
            ->whereNotNull('exp_vip')
            ->orderByDesc('exp_vip')
            ->first();

        $extendFrom = ($activeSub && $activeSub->exp_vip && Carbon::parse($activeSub->exp_vip)->isFuture())
            ? Carbon::parse($activeSub->exp_vip)
            : $now->copy();

        $endDate = $extendFrom->copy()->addMonths($planMonths);

        Subscribe::query()
            ->where('user_id', $userId)
            ->where('status', 1)
            ->update(['status' => 0]);

        $subscribe = new Subscribe();
        $subscribe->user_id = $userId;
        $subscribe->vip = $planMonths;
        $subscribe->start_vip = $now;
        $subscribe->exp_vip = $endDate;
        $subscribe->type = 3;
        $subscribe->register_date = $now;
        $subscribe->method = 1;
        $subscribe->status = 1;

        if (Schema::hasColumn('subscribes', 'price')) {
            $subscribe->price = $amount;
        }

        if (Schema::hasColumn('subscribes', 'tracking_code')) {
            $subscribe->tracking_code = $trackingCode;
        }

        if (Schema::hasColumn('subscribes', 'created_at')) {
            $subscribe->created_at = $now;
        }

        if (Schema::hasColumn('subscribes', 'updated_at')) {
            $subscribe->updated_at = $now;
        }

        $subscribe->save();

        return (int) $subscribe->id;
    }

    public function sendActivationSms(int $userId, int $planMonths): void
    {
        $days = max(0, $planMonths * 30);

        if ($days > 0) {
            app(VipActivationSmsService::class)->sendByUserId($userId, $days);
        }
    }
}