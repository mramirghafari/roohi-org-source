<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\LbankBalanceCheckQueue;
use App\Models\UserBalanceLog;

class LbankBalancePoll extends Command
{
    protected $signature = 'lbank:balance:poll {--limit=24} {--step_ms=2500}';
    protected $description = 'Poll LBank total balance safely and move processed users out of pending queue';

    public function handle(): int
    {
        $limit  = (int) $this->option('limit');
        $stepMs = (int) $this->option('step_ms');

        $url = 'https://roohi.trade/newbot/test_user_info.php';

        // فقط pending ها
        $rows = LbankBalanceCheckQueue::query()
            ->where('status', LbankBalanceCheckQueue::STATUS_PENDING)
            ->orderBy('id', 'asc')
            ->limit($limit)
            ->get();

        if ($rows->isEmpty()) {
            $this->info('No pending users.');
            return self::SUCCESS;
        }

        foreach ($rows as $row) {

            // lock (pending -> processing)
            $locked = LbankBalanceCheckQueue::query()
                ->where('id', $row->id)
                ->where('status', LbankBalanceCheckQueue::STATUS_PENDING)
                ->update(['status' => LbankBalanceCheckQueue::STATUS_PROCESSING]);

            if (!$locked) {
                continue;
            }

            // فاصله امن 2.5s
            usleep($stepMs * 1000);

            try {
                $response = Http::timeout(15)->acceptJson()->get($url, [
                    'mch' => $row->lbank_uid,
                ]);

                if (!$response->ok()) {
                    // خطا => برگرده pending (یا error)
                    $this->markPending($row->id);
                    continue;
                }

                $json = $response->json();

                if (
                    !is_array($json) ||
                    !isset($json['success'], $json['data']['assets']['total']) ||
                    $json['success'] !== true
                ) {
                    $this->markPending($row->id);
                    continue;
                }

                $newTotal = (string) $json['data']['assets']['total'];

                $lastLog = UserBalanceLog::query()
                    ->where('lbank_uid', $row->lbank_uid)
                    ->orderBy('time', 'desc')
                    ->first();

                $oldTotal = $lastLog ? (string) $lastLog->balance : null;

                // فقط در صورت تغییر ذخیره کن
                if ($oldTotal === null || $this->normalize($oldTotal) !== $this->normalize($newTotal)) {
                    UserBalanceLog::query()->create([
                        'user_id'   => $row->user_id,
                        'lbank_uid' => $row->lbank_uid,
                        'balance'   => $newTotal,
                        'time'      => now(),
                    ]);

                    $this->info("LOGGED {$row->lbank_uid}: {$oldTotal} -> {$newTotal}");
                }

                // ✅ مهم: اینجا دیگه pending نشه، DONE بشه تا از صف خارج شه
                $this->markDone($row->id);

            } catch (\Throwable $e) {
                // اگر exception شد، pending کن تا دوباره تلاش بشه
                $this->markPending($row->id);
            }
        }

        return self::SUCCESS;
    }

    private function markPending(int $id): void
    {
        LbankBalanceCheckQueue::query()->where('id', $id)->update([
            'status' => LbankBalanceCheckQueue::STATUS_PENDING,
        ]);
    }

    private function markDone(int $id): void
    {
        LbankBalanceCheckQueue::query()->where('id', $id)->update([
            'status' => LbankBalanceCheckQueue::STATUS_DONE,
        ]);
    }

    private function normalize(string $v): string
    {
        $v = trim($v);
        if (str_contains($v, '.')) {
            $v = rtrim($v, '0');
            $v = rtrim($v, '.');
        }
        return $v;
    }
}
