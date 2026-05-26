<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroupCommissionRule extends Model
{
    use HasFactory;

    public const EVENT_REFERRAL_REGISTER = 'referral_register';
    public const EVENT_REFERRAL_VIP_PURCHASE = 'referral_vip_purchase';

    public const EVENTS = [
        self::EVENT_REFERRAL_REGISTER,
        self::EVENT_REFERRAL_VIP_PURCHASE,
    ];

    protected $fillable = [
        'user_group_id',
        'event',
        'level',
        'stars_reward',
        'toman_reward',
        'usdt_reward',
        'commission_percent',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'stars_reward' => 'decimal:8',
            'toman_reward' => 'decimal:8',
            'usdt_reward' => 'decimal:8',
            'commission_percent' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }
}
