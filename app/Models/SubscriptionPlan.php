<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'icon_path',
        'duration_months',
        'gateway_price',
        'gateway_enabled',
        'card_to_card_price',
        'card_to_card_enabled',
        'card_number',
        'card_owner',
        'card_accounts',
        'features',
        'receipt_required',
        'payer_card_required',
        'paid_at_required',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration_months' => 'integer',
            'gateway_price' => 'integer',
            'gateway_enabled' => 'boolean',
            'card_to_card_price' => 'integer',
            'card_to_card_enabled' => 'boolean',
            'card_accounts' => 'array',
            'features' => 'array',
            'receipt_required' => 'boolean',
            'payer_card_required' => 'boolean',
            'paid_at_required' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}