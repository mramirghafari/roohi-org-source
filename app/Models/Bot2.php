<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BalanceLog;


/**
 * @property int $id
 * @property string $user_id
 * @property string|null $name
 * @property string|null $last_name
 * @property string|null $username
 * @property string|null $mobile
 * @property string|null $nam
 * @property int $step
 * @property int $status
 * @property int|null $vip
 * @property string $start_vip // NOT NULL
 * @property string|null $exp_vip
 * @property string|null $daramad
 * @property string|null $job
 * @property int|null $age
 * @property string|null $sarmaye
 * @property string|null $problem
 * @property string|null $reason
 * @property string|null $workTime
 * @property string|null $endSteps
 * @property int $broadcast
 * @property string|null $otp
 * @property string|null $otp_created_at
 * @property int $isVerify
 * @property string|null $lbank_uid
 * @property string $register_date
 * @property string $ref_code // NOT NULL
 * @property string|null $invite_by
 * @property string|null $camp_num
 * @property int $credit
 * @property int $2hpm
 * @property int $1hpm
 * @property int $nimhpm
 */
class Bot2 extends Model
{
    use HasFactory;

    // 1. اتصال به جدول
    protected $table = 'new_users';

    // 2. تنظیم کلید اصلی (پیش‌فرض لاراول ID است، اما برای اطمینان ذکر می‌کنیم)
    protected $primaryKey = 'id';

    // 3. تنظیم timestamps
    // از آنجایی که 'register_date' داری و ستون‌های created_at و updated_at استاندارد لاراول را نداری،
    // باید timestamps را غیرفعال کنیم.
    public $timestamps = false;

    // 4. ستون‌هایی که باید از Eloquent به صورت Carbon objects بازیابی شوند (Datetime)
    // این کار محاسبات زمانی را راحت‌تر می‌کند.
    protected $dates = [
        'start_vip',
        'exp_vip',
        'endSteps',
        'otp_created_at',
        'register_date',
    ];

    // 5. ستون‌های Fillable (برای Mass Assignment)
    protected $fillable = [
        'user_id', 'name', 'last_name', 'username', 'mobile', 'nam', 'step',
        'status', 'vip', 'start_vip', 'exp_vip', 'daramad', 'job', 'age',
        'sarmaye', 'problem', 'reason', 'workTime', 'endSteps', 'broadcast',
        'otp', 'otp_created_at', 'isVerify', 'lbank_uid', 'register_date',
        'ref_code', 'invite_by', 'camp_num', 'credit', '2hpm', '1hpm', 'nimhpm',
    ];

    // 6. ستون‌های Cast (برای اطمینان از نوع داده‌ها)
    protected $casts = [
        'step' => 'integer',
        'status' => 'integer',
        'vip' => 'integer',
        'broadcast' => 'integer',
        'isVerify' => 'integer',
        'credit' => 'integer',
        // توجه: نام ستون‌های شروع شونده با عدد مثل '2hpm' را در اینجا ننویس.
    ];
    
    // 7. (اختیاری) اگر ستون‌هایی مثل '2hpm' مشکل ایجاد کرد، می‌توانی از Mutator استفاده کنی.
    // اما در حالت عادی Eloquent باید آن‌ها را هندل کند.


   public function balanceLogs()
    {
        return $this->hasMany(
            BalanceLog::class,
            'user_id',   // foreign key
            'user_id'    // local key
        )->orderByDesc('time'); // از آخرین به اولین
    }

    public function latestBalance()
{
    return $this->hasOne(
        BalanceLog::class,
        'user_id',
        'user_id'
    )->latestOfMany('time');
}
}
