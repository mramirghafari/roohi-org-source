<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchiveNotif extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'archive_notifs';

    protected $fillable = [
        'title',
        'content',
        'created_by',
        'usersGroup',
        'sms',
        'status'
    ];

    protected $casts = [
        'usersGroup' => 'integer',
        'sms'        => 'boolean',
    ];

    protected $appends = [
        'sent_notifs_count',
        'read_notifs_count',
    ];

    /* ================= Relations ================= */

    // سازنده نوتیف (ادمین / سیستم)
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // نوتیف‌های ساخته شده از این آرشیو
    public function notifs(): HasMany
    {
        return $this->hasMany(Notifs::class, 'archive_notif_id');
    }

    /* ================= Accessors ================= */

    // تعداد نوتیف‌های ارسالی
    public function getSentNotifsCountAttribute()
    {
        return $this->notifs()->count();
    }

    // تعداد نوتیف‌های خوانده شده (status = 1 یا بالاتر)
    public function getReadNotifsCountAttribute()
    {
        return $this->notifs()->where('status', '>=', 1)->count();
    }
}
