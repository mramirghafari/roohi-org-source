<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceLog extends Model
{
    /**
     * نام جدول در دیتابیس
     */
    protected $table = 'new_users_balance_log';

    /**
     * کلید اصلی
     */
    protected $primaryKey = 'id';

    /**
     * Auto Increment فعال است
     */
    public $incrementing = true;

    /**
     * نوع کلید اصلی
     */
    protected $keyType = 'int';

    /**
     * چون ستون‌های created_at / updated_at نداریم
     */
    public $timestamps = false;

    /**
     * ستون قابل پر شدن (Mass Assignment)
     */
    protected $fillable = [
        'user_id',
        'lbank_uid',
        'balance',
        'time',
    ];

    /**
     * Castها (در صورت نیاز)
     * balance رشته است ولی می‌توان هنگام استفاده عددی دید
     */
    protected $casts = [
        'time' => 'datetime',
    ];
}
