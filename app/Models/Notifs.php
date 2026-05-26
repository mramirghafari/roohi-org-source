<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifs extends Model
{
    use HasFactory;

    protected $table = 'notifs';

    protected $fillable = [
        'user_id',
        'archive_notif_id',
        'title',
        'content',
        'status',
        'sms',
        'sendSmsAt',
    ];

    protected $casts = [
        'status' => 'integer',
        'sms' => 'integer',
    ];

    /* ================= Relations ================= */

    // کاربر دریافت‌کننده
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // نوتیف مرجع (آرشیو)
    public function archive(): BelongsTo
    {
        return $this->belongsTo(ArchiveNotif::class, 'archive_notif_id');
    }
}
