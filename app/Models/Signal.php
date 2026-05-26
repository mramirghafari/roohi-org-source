<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    protected $table = 'agt_signals';
    protected $fillable = ['symbol','type','entryPrice1','entryPrice2','sl','target1','target2','target3','target4','target5','laverege','profit','content','status','user_id','sms','tracking_code','isVisible','photo','result'];

    public function targetProfitPercent(int $targetLevel): ?float
    {
        if ($targetLevel < 1 || $targetLevel > 5) {
            return null;
        }

        $entry = (float) $this->entryPrice1;
        $leverage = (float) $this->laverege;
        $target = (float) $this->{"target{$targetLevel}"};
        $type = (int) $this->type;

        if ($entry <= 0 || $target <= 0 || $leverage <= 0) {
            return null;
        }

        if ($type === 2) {
            $profit = (($target - $entry) * $leverage / $entry) * 100;
        } elseif ($type === 1) {
            $profit = (($entry - $target) * $leverage / $entry) * 100;
        } else {
            return null;
        }

        return round($profit, 2);
    }

    public function touchedProfitPercent(): ?float
    {
        $targetLevel = min((int) $this->tp_level, 5);

        if ($targetLevel < 1) {
            return null;
        }

        return $this->targetProfitPercent($targetLevel);
    }

    public function smsJobs()
    {
        return $this->hasMany(SignalSmsJob::class);
    }

}
