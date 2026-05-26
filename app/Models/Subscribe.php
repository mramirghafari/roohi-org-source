<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Subscribe extends Model
{
    use HasFactory;

    protected $table = 'subscribes';

    // چون user_id شما varchar است و احتمالاً timestamps هم ندارید
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'vip',
        'start_vip',
        'exp_vip',
        'type',
        'register_date',
        'method',
        'status',
    ];

    protected $casts = [
        'vip'           => 'integer',  // tinyint
        'type'          => 'integer',  // tinyint
        'method'        => 'integer',  // tinyint
        'status'        => 'integer',  // tinyint
        'start_vip'     => 'datetime',
        'exp_vip'       => 'datetime',
        'register_date' => 'datetime',
    ];

    /**
     * مقادیر پیشنهادی برای type:
     * 1 = telegram_bot
     * 2 = admin_grant
     * 3 = purchase
      * 4 = demo
     *
     * method می‌تونه روش پرداخت/کانال خرید باشه (اختیاری)
     */

    public function user()
    {
        // نکته مهم: چون user_id در این جدول varchar است
        // باید کلید لوکال User را درست ست کنیم (معمولاً 'id')
        // اگر در جدول users، id عددی است ولی شما اینجا شماره موبایل/کد دیگری ذخیره می‌کنید،
        // باید localKey را همان فیلد درست (مثلاً 'mobile') بگذارید.
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * VIP فعال؟
     */
    public function isVipActive(): bool
    {
        if ((int)$this->status !== 1) return false;
        if ((int)$this->vip !== 1) return false;
        if (!$this->start_vip) return false;
        if (!$this->exp_vip) return false;

        return now()->between($this->start_vip, $this->exp_vip);
    }

    /**
     * روزهای باقیمانده (اگر فعال نباشد 0)
     */
    public function remainingDays(): int
    {
        if (!$this->isVipActive()) return 0;
        return now()->diffInDays($this->exp_vip, false);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('status', 1);
        // اگر تاریخ انقضا هم داری، اینو هم اضافه کن:
        // ->whereNotNull('exp_vip')->where('exp_vip', '>', now());
    }
}
