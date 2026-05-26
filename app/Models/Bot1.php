<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot1 extends Model
{
    use HasFactory;

    // اتصال به جدول
    protected $table = 'bot1_users';

    // اگر کلید اصلی ستونی غیر از 'id' بود، اینجا باید تعریفش کنی:
    protected $primaryKey = 'id';

    // اگر جدول timestamps ندارد (created_at, updated_at)، این خط را اضافه کن:
    public $timestamps = false;

    // ستون‌هایی که می‌تونی mass assign کنی
    protected $fillable = [
        'user_id',
        'name',
        'last_name',
        'username',
        'mobile',
        'nam',
        'step',
        'status',
        'vip',
        'start_vip',
        'exp_vip',
        'broadcast',
        'register_date',
    ];
}
