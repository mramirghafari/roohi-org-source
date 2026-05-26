<?php

namespace App\Console\Commands;

use App\Models\SignalSmsJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Kavenegar;

class SendSignalSmsBatch extends Command
{
    protected $signature = 'signal:sms-batch {--limit=200}';
    protected $description = 'Send pending signal SMS in batches';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $jobs = DB::transaction(function () use ($limit) {
            $items = SignalSmsJob::with(['user', 'signal'])
            ->where('status', 0)
            ->where(function ($q) {
                $q->whereNull('scheduled_at')
                ->orWhere('scheduled_at', '<=', now());
            })
            ->orderBy('id')
            ->limit($limit)
            ->lockForUpdate()
            ->get();

            foreach ($items as $it) {
                $it->increment('attempts');
            }

            return $items;
        });

        foreach ($jobs as $job) {
            try {
                // TODO: اینجا سرویس SMS خودت رو صدا بزن
                // مثال:
                // $phone = $job->user->phone;
                // $text  = "سیگنال جدید ثبت شد. کد: {$job->signal_id}";
                // SmsService::send($phone, $text);

                $receptor = $job->user->mobile;        //This is the Sender number
                $template = "newsignalBot";
                $type = "sms";
                $token = $job->signal->tracking_code;
                $token2 = "";
                $token3 = "";
                $result = Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type); 

                $job->update([
                    'status' => 1,
                    'sent_at' => now(),
                    'last_error' => null,
                ]);
            } catch (\Throwable $e) {
                $job->update([
                    'status' => $job->attempts >= 3 ? 2 : 0,
                    'failed_at' => $job->attempts >= 3 ? now() : null,
                    'last_error' => mb_substr($e->getMessage(), 0, 2000),
                ]);
            }
        }

        $this->info("Processed: " . $jobs->count());

        $processedSignalIds = $jobs
            ->pluck('signal_id')
            ->unique()
            ->values();

        foreach ($processedSignalIds as $signalId) {

            $hasPending = SignalSmsJob::where('signal_id', $signalId)
                ->where('status', 0)
                ->exists();

            if (!$hasPending) {
                // همه‌ی SMS های این سیگنال یا ارسال شدن یا fail شدن
                SignalSmsJob::where('signal_id', $signalId)->delete();
            }
        }


        return self::SUCCESS;
    }
}
