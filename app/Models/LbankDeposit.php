<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $user_id      // شناسه داخلی کاربر
 * @property string|null $lbank_uid // شناسه کاربری LBank
 * @property float $deposit        // مقدار واریزی
 * @property string $time         // زمان واریزی
 */
class LbankDeposit extends Model
{
    use HasFactory;

    // 1. اتصال به جدول
    protected $table = 'new_users_deposits';

    // 2. تنظیم کلید اصلی
    protected $primaryKey = 'id';

    // 3. این جدول احتمالاً فیلدهای created_at و updated_at را ندارد.
    public $timestamps = false;

    // 4. ستون‌های زمان‌بندی (DateTime)
    // ستون 'time' به عنوان ستون زمان در دیتابیس شما تعریف شده است.
    protected $dates = [
        'time',
    ];

    // 5. ستون‌های Fillable (برای Mass Assignment)
    protected $fillable = [
        'user_id',
        'lbank_uid',
        'deposit',
        'time',
    ];

    // 6. ستون‌های Cast (برای اطمینان از نوع داده‌ها)
    protected $casts = [
        'id' => 'integer',
        'deposit' => 'float', // مقدار واریزی معمولاً باید float یا decimal باشد
    ];

    // 7. (اختیاری) تعریف رابطه با مدل Bot2
    // فرض می‌کنیم user_id در این جدول به user_id در جدول Bot2 اشاره دارد.
    public function user()
    {
        // در مدل Bot2، کلید اصلی user_id نیست بلکه id است.
        // اما از روی طرح جدول قبلی (Bot2) فیلدی به نام 'user_id' وجود داشت.
        // اگر user_id در new_users_deposits همان ID ردیف در new_users (Bot2) باشد:
        // return $this->belongsTo(Bot2::class, 'user_id', 'id');
        
        // اگر user_id در اینجا به ستون user_id در Bot2 اشاره دارد:
        return $this->belongsTo(Bot2::class, 'user_id', 'user_id');
        
        // **نکته:** بهتر است این را بر اساس ساختار واقعی دیتابیس خودت نهایی کنی.
        // من فرض می‌کنم user_id در اینجا همان ID در جدول Bot2 نیست و یک شناسه خارجی است.
        // اگر user_id در جدول Bot2 یونیک است، این رابطه کار می‌کند.
    }
}
