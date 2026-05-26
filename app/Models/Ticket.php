<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public const DEPARTMENTS = [
        'financial' => 'مالی',
        'technical' => 'فنی',
        'support' => 'پشتیبانی',
    ];

    public const STATUS_OPEN = 'open';
    public const STATUS_ANSWERED_BY_USER = 'answered_by_user';
    public const STATUS_ANSWERED_BY_SUPPORT = 'answered_by_support';
    public const STATUS_CLOSED = 'closed';

    public const PRIORITY_LOW = 'low';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    public const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_ANSWERED_BY_USER,
        self::STATUS_ANSWERED_BY_SUPPORT,
        self::STATUS_CLOSED,
    ];

    public const PRIORITIES = [
        self::PRIORITY_LOW => 'کم',
        self::PRIORITY_HIGH => 'زیاد',
        self::PRIORITY_URGENT => 'فوری',
    ];

    protected $fillable = [
        'user_id',
        'department',
        'subject',
        'tracking_code',
        'priority',
        'status',
        'assigned_to',
        'last_reply_by',
        'last_reply_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'last_reply_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function lastReplier()
    {
        return $this->belongsTo(User::class, 'last_reply_by');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
}
