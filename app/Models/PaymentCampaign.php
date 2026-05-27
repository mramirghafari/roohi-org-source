<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class PaymentCampaign extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'amount',
        'currency',
        'description',
        'original_price',
        'current_price',
        'capacity',
        'display_capacity',
        'button_text',
        'success_message',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'original_price' => 'integer',
            'current_price' => 'integer',
            'capacity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(PaymentRegistration::class, 'payment_campaign_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCurrentPriceAttribute($value): int
    {
        return (int) ($value ?? $this->amount ?? 0);
    }

    public function getOriginalPriceAttribute($value): int
    {
        return (int) ($value ?? $this->amount ?? 0);
    }

    public function getRemainingCapacityAttribute(): ?int
    {
        if (!$this->capacity) {
            return null;
        }

        $paidCount = $this->registrations_success_count
            ?? (
                Schema::hasTable('payment_registrations')
                && Schema::hasColumn('payment_registrations', 'payment_campaign_id')
                    ? $this->registrations()->where('status', PaymentRegistration::STATUS_SUCCESS)->count()
                    : 0
            );

        return max(0, (int) $this->capacity - (int) $paidCount);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->capacity > 0 && $this->remaining_capacity <= 0;
    }
}
