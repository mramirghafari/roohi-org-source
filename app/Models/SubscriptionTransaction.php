<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionTransaction extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_ERROR = 'error';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'user_id',
        'admin_user_id',
        'subscription_plan_id',
        'subscribe_id',
        'plan_months',
        'amount',
        'currency',
        'payment_method',
        'ref_id',
        'card_pan',
        'receipt_path',
        'payer_card_number',
        'manual_paid_at',
        'gateway_status',
        'gateway_code',
        'status',
        'message',
        'activated_at',
        'admin_reviewed_at',
        'paid_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'plan_months' => 'integer',
            'amount' => 'integer',
            'gateway_code' => 'integer',
            'manual_paid_at' => 'datetime',
            'activated_at' => 'datetime',
            'admin_reviewed_at' => 'datetime',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscribe()
    {
        return $this->belongsTo(Subscribe::class);
    }

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }
}
