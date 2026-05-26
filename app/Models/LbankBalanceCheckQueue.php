<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LbankBalanceCheckQueue extends Model
{
    protected $table = 'job_check_uid_lbank_balance';

    public $timestamps = false; // ✅ مهم: چون جدول created_at/updated_at ندارد

    protected $fillable = [
        'user_id',
        'lbank_uid',
        'status',
        'last_checked_at',
        'last_error',
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
        'status' => 'integer',
    ];

    // Status codes پیشنهادی
    public const STATUS_PENDING    = 0; // آماده پردازش
    public const STATUS_PROCESSING = 1; // در حال پردازش
    public const STATUS_DONE       = 2; // انجام شد (موفق)
    public const STATUS_ERROR      = 9; // خطا

    public function scopePending($q)
    {
        return $q->where('status', self::STATUS_PENDING);
    }
}
