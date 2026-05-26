<?php

namespace App\Services;

use App\Models\Subscribe;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Log;
use Kavenegar;

class VipActivationSmsService
{
    public function sendByUserId(
        int $userId,
        int $days,
        CarbonInterface|string|null $startDate = null,
        CarbonInterface|string|null $endDate = null
    ): void
    {
        $this->sendActiveVipByUserId($userId, $days, $startDate, $endDate);
    }

    public function sendActiveVipByUserId(
        int $userId,
        int $days,
        CarbonInterface|string|null $startDate = null,
        CarbonInterface|string|null $endDate = null
    ): void
    {
        $user = User::query()->find($userId);
        if (!$user) {
            return;
        }

        $this->sendActiveVip($user, $days, $startDate, $endDate);
    }

    public function sendAddDaysByUserId(int $userId, int $days): void
    {
        $user = User::query()->find($userId);
        if (!$user) {
            return;
        }

        $this->sendAddDaysVip($user, $days);
    }

    public function send(
        User $user,
        int $days,
        CarbonInterface|string|null $startDate = null,
        CarbonInterface|string|null $endDate = null
    ): void
    {
        $this->sendActiveVip($user, $days, $startDate, $endDate);
    }

    private function sendActiveVip(
        User $user,
        int $days,
        CarbonInterface|string|null $startDate = null,
        CarbonInterface|string|null $endDate = null
    ): void
    {
        $mobile = trim((string) ($user->mobile ?? ''));
        if ($mobile === '' || $days <= 0) {
            return;
        }

        [$resolvedStartDate, $resolvedEndDate] = $this->resolveVipDates($user->id, $startDate, $endDate);

        if (!$resolvedStartDate || !$resolvedEndDate) {
            Log::warning('VIP activation sms skipped because vip dates are missing', [
                'user_id' => $user->id,
                'days' => $days,
            ]);
            return;
        }

        $token = $days . 'روزه';
        $token2 = Verta::instance($resolvedStartDate)->format('Y/m/d');
        $token3 = Verta::instance($resolvedEndDate)->format('Y/m/d');

        $this->sendLookup($user, $mobile, $token, $token2, $token3, 'activeVIP', $days);
    }

    private function sendAddDaysVip(User $user, int $days): void
    {
        $mobile = trim((string) ($user->mobile ?? ''));
        if ($mobile === '' || $days <= 0) {
            return;
        }

        $endDate = $this->resolveVipEndDate($user->id);
        if (!$endDate) {
            Log::warning('addDaysVIP sms skipped because vip end date is missing', [
                'user_id' => $user->id,
                'days' => $days,
            ]);
            return;
        }

        $token = (string) $days;
        $token2 = Verta::instance($endDate)->format('Y/m/d');

        $this->sendLookup($user, $mobile, $token, $token2, '', 'addDaysVIP', $days);
    }

    private function sendLookup(
        User $user,
        string $mobile,
        string $token,
        string $token2,
        string $token3,
        string $template,
        int $days
    ): void
    {

        try {
            Kavenegar::VerifyLookup(
                $mobile,
                $token,
                $token2,
                $token3,
                $template,
                'sms'
            );
        } catch (\Throwable $exception) {
            Log::error('VIP activation sms failed', [
                'user_id' => $user->id,
                'mobile' => $mobile,
                'days' => $days,
                'template' => $template,
                'token' => $token,
                'token2' => $token2,
                'token3' => $token3,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function resolveVipDates(
        int $userId,
        CarbonInterface|string|null $startDate,
        CarbonInterface|string|null $endDate
    ): array {
        $resolvedStartDate = $startDate ? Carbon::parse($startDate) : null;
        $resolvedEndDate = $endDate ? Carbon::parse($endDate) : null;

        if ($resolvedStartDate && $resolvedEndDate) {
            return [$resolvedStartDate, $resolvedEndDate];
        }

        $activeSubscribe = Subscribe::query()
            ->where('user_id', $userId)
            ->where('status', 1)
            ->whereNotNull('start_vip')
            ->whereNotNull('exp_vip')
            ->orderByDesc('exp_vip')
            ->first();

        if (!$activeSubscribe) {
            return [null, null];
        }

        return [
            Carbon::parse($activeSubscribe->start_vip),
            Carbon::parse($activeSubscribe->exp_vip),
        ];
    }

    private function resolveVipEndDate(int $userId): ?Carbon
    {
        $activeSubscribe = Subscribe::query()
            ->where('user_id', $userId)
            ->where('status', 1)
            ->whereNotNull('exp_vip')
            ->orderByDesc('exp_vip')
            ->first();

        if (!$activeSubscribe) {
            return null;
        }

        return Carbon::parse($activeSubscribe->exp_vip);
    }
}
