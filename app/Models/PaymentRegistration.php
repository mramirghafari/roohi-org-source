<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRegistration extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_ERROR = 'error';
    public const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'payment_campaign_id',
        'full_name',
        'mobile',
        'amount',
        'currency',
        'tracking_code',
        'authority',
        'ref_id',
        'card_pan',
        'gateway_status',
        'gateway_code',
        'status',
        'message',
        'paid_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'gateway_code' => 'integer',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(PaymentCampaign::class, 'payment_campaign_id');
    }
}
