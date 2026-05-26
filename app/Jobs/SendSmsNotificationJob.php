<?php

namespace App\Jobs;

use App\Models\Notifs;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Kavenegar;
use Carbon\Carbon;

class SendSmsNotificationJob implements ShouldQueue
{
    use Queueable;

    protected $notifId;

    /**
     * Create a new job instance.
     */
    public function __construct($notifId)
    {
        $this->notifId = $notifId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $notif = Notifs::find($this->notifId);
            
            if (!$notif || !$notif->user || !$notif->user->mobile) {
                Log::warning("Notification {$this->notifId} or user mobile not found");
                return;
            }

            $receptor = $notif->user->mobile;
            $template = "sendNotif";
            $type = "sms";
            $token = "کاربر";
            $token2 = "";
            $token3 = "";

            // ارسال SMS از طریق Kavenegar
            $result = Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type);

            // ذخیره زمان ارسال SMS
            $notif->update(['sendSmsAt' => Carbon::now()]);

            Log::info("SMS sent successfully for notification {$this->notifId}. Result: {$result}");

        } catch (\Exception $e) {
            Log::error("Failed to send SMS for notification {$this->notifId}. Error: " . $e->getMessage());
            
            // اگر 3 بار تلاش شناسایی شد، کار را متوقف کن
            if ($this->attempts() >= 3) {
                Log::error("Max attempts reached for notification {$this->notifId}. Stopping job.");
                $this->fail($e);
            } else {
                // دوباره تلاش کن
                $this->release(60); // 60 ثانیه بعد دوباره تلاش کن
            }
        }
    }
}
