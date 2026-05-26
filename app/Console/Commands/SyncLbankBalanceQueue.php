<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LbankBalanceCheckQueue;
use App\Models\User;

class SyncLbankBalanceQueue extends Command
{
    protected $signature = 'lbank:queue:sync';
    protected $description = 'Populate/Sync job_check_uid_lbank_balance from eligible users';

    public function handle(): int
    {
        /**
         * ✅ اینجا باید دقیقاً مطابق دیتابیس خودت شرط بزنی:
         * - یوزر lbank_uid داشته باشه
         * - اشتراک فعال داشته باشه (مثلاً subscribes.status = 1)
         *
         * من فرض کردم:
         * users.lbank_uid
         * subscribes جدول اشتراک و ستون status
         */

        $eligible = User::query()
            ->whereNotNull('lbank_uid')
            ->where('lbank_uid', '!=', '')
            ->whereExists(function ($q) {
                $q->selectRaw(1)
                    ->from('subscribes')
                    ->whereColumn('subscribes.user_id', 'users.id')
                    ->where('subscribes.status', 1);
            })
            ->select('id', 'lbank_uid')
            ->get();

        $count = 0;

        foreach ($eligible as $u) {
            LbankBalanceCheckQueue::query()->updateOrCreate(
                ['lbank_uid' => $u->lbank_uid], // کلید یکتا
                [
                    'user_id' => $u->id,
                    'status'  => LbankBalanceCheckQueue::STATUS_PENDING,
                ]
            );
            $count++;
        }

        $this->info("✅ Synced {$count} users into job_check_uid_lbank_balance");
        return self::SUCCESS;
    }
}
