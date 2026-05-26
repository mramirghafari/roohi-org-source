<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kavenegar;

class SendVipExpiryReminderSms extends Command
{
    protected $signature = 'vip:send-expiry-reminders';

    protected $description = 'Send SMS reminders for subscriptions nearing expiry (10, 7, 3, 1 days left).';

    public function handle(): int
    {
        $thresholds = [10, 7, 3, 1];
        $today = Carbon::now('Asia/Tehran')->startOfDay();
        $processed = 0;
        $sent = 0;

        Subscribe::query()
            ->where('status', 1)
            ->whereNotNull('exp_vip')
            ->with(['user:id,mobile'])
            ->orderBy('id')
            ->chunk(500, function ($rows) use ($thresholds, $today, &$processed, &$sent) {
                foreach ($rows as $subscribe) {
                    $processed++;

                    $mobile = trim((string) ($subscribe->user?->mobile ?? ''));
                    if ($mobile === '') {
                        continue;
                    }

                    $expiryDate = Carbon::parse($subscribe->exp_vip, 'Asia/Tehran')->startOfDay();
                    $daysLeft = $today->diffInDays($expiryDate, false);

                    if (!in_array($daysLeft, $thresholds, true)) {
                        continue;
                    }

                    $alreadySent = DB::table('subscription_expiry_sms_logs')
                        ->where('subscribe_id', $subscribe->id)
                        ->where('days_left', $daysLeft)
                        ->where('expires_on', $expiryDate->toDateString())
                        ->exists();

                    if ($alreadySent) {
                        continue;
                    }

                    try {
                        Kavenegar::VerifyLookup(
                            $mobile,
                            (string) $daysLeft,
                            '',
                            '',
                            'XdayExpiryDateRoohiTrade',
                            'sms'
                        );

                        DB::table('subscription_expiry_sms_logs')->insert([
                            'user_id' => $subscribe->user_id,
                            'subscribe_id' => $subscribe->id,
                            'days_left' => $daysLeft,
                            'expires_on' => $expiryDate->toDateString(),
                            'mobile' => $mobile,
                            'template' => 'XdayExpiryDateRoohiTrade',
                            'sent_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $sent++;
                    } catch (\Throwable $exception) {
                        Log::error('VIP expiry reminder sms failed', [
                            'subscribe_id' => $subscribe->id,
                            'user_id' => $subscribe->user_id,
                            'mobile' => $mobile,
                            'days_left' => $daysLeft,
                            'template' => 'XdayExpiryDateRoohiTrade',
                            'error' => $exception->getMessage(),
                        ]);
                    }
                }
            });

        $this->info("Processed {$processed} subscriptions. Sent {$sent} reminders.");

        return self::SUCCESS;
    }
}
