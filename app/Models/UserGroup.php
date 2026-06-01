<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    public const ASSIGNMENT_SALES_SUPPORT = 'sales_support';
    public const ASSIGNMENT_TECHNICAL_SUPPORT = 'technical_support';
    public const ASSIGNMENT_SALES_EXPERT = 'sales_expert';
    public const ASSIGNMENT_SALES_MANAGER = 'sales_manager';

    public const ASSIGNMENT_ROLES = [
        self::ASSIGNMENT_SALES_SUPPORT => 'پشتیبان فروش',
        self::ASSIGNMENT_TECHNICAL_SUPPORT => 'پشتیبان فنی',
        self::ASSIGNMENT_SALES_EXPERT => 'کارشناس فروش',
        self::ASSIGNMENT_SALES_MANAGER => 'مدیر فروش',
    ];

    public const COMMISSION_MODE_INHERIT = 'inherit';
    public const COMMISSION_MODE_CUSTOM = 'custom';

    public const COMMISSION_MODES = [
        self::COMMISSION_MODE_INHERIT,
        self::COMMISSION_MODE_CUSTOM,
    ];

    public const PURCHASE_REWARD_NONE = 'none';
    public const PURCHASE_REWARD_TOMAN = 'toman';
    public const PURCHASE_REWARD_USDT = 'usdt';
    public const PURCHASE_REWARD_BOTH = 'both';

    public const PURCHASE_REWARD_MODES = [
        self::PURCHASE_REWARD_NONE,
        self::PURCHASE_REWARD_TOMAN,
        self::PURCHASE_REWARD_USDT,
        self::PURCHASE_REWARD_BOTH,
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'commission_mode',
        'attraction_stars_reward',
        'purchase_commission_percent',
        'purchase_reward_mode',
        'purchase_reward_toman',
        'purchase_reward_usdt',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'commission_mode' => 'string',
            'attraction_stars_reward' => 'decimal:8',
            'purchase_commission_percent' => 'decimal:2',
            'purchase_reward_toman' => 'decimal:8',
            'purchase_reward_usdt' => 'decimal:8',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'user_group_user', 'user_group_id', 'user_id')
            ->using(UserGroupMember::class)
            ->withPivot(['joined_at', 'notes'])
            ->withTimestamps();
    }

    public function supportAccounts()
    {
        return $this->belongsToMany(User::class, 'user_group_support_accounts', 'user_group_id', 'support_user_id')
            ->using(UserGroupSupportAccount::class)
            ->withPivot(['assigned_by', 'assignment_role'])
            ->withTimestamps();
    }

    public function memberAssignments()
    {
        return $this->hasMany(UserGroupMember::class, 'user_group_id');
    }

    public function supportAssignments()
    {
        return $this->hasMany(UserGroupSupportAccount::class, 'user_group_id');
    }

    public function commissionRules()
    {
        return $this->hasMany(UserGroupCommissionRule::class, 'user_group_id');
    }
}
