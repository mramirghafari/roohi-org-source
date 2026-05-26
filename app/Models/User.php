<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


use App\Models\Bot1;
use App\Models\Bot2;
use App\Models\Subscribe;
use App\Models\UserApiLbank;
use App\Models\Notifs;
use App\Models\UserBalanceLog;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\SupportDepartmentAssignment;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use App\Models\UserGroup;
use App\Models\UserGroupMember;
use App\Models\UserGroupSupportAccount;
use App\Models\RemoteAccessToken;



class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'username',
        'nam',
        'gender',
        'birthdate',
        'email',
        'password',
        'lbank_uid',
        'coincall_uid',
        'admin_note',
        'is_support',
        // اگر فیلد mobile داری و در fillable نیست، اضافه کن
        // 'mobile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * اینا در JSON خروجی (مثلاً API) auto-append میشن
     */
    protected $appends = [
        'vip_membership',
        'has_active_vip',
        'vip_remaining_days',
    ];

    /*
    |--------------------------------------------------------------------------
    | Bot Relations (صرفاً برای استفاده‌های دیگر)
    | توجه: از این‌ها برای VIP حساب کردن استفاده نمی‌کنیم.
    |--------------------------------------------------------------------------
    */
    public function bot1()
    {
        return $this->hasOne(Bot1::class, 'mobile', 'mobile');
    }

    public function bot2()
    {
        return $this->hasOne(Bot2::class, 'mobile', 'mobile');
    }

    /*
    |--------------------------------------------------------------------------
    | Subscribe Relations (منبع اصلی VIP)
    |--------------------------------------------------------------------------
    */
    public function subscribes()
    {
        return $this->hasMany(Subscribe::class, 'user_id', 'id');
    }

    /**
     * Query رکوردهای VIP فعال از subscribes (فقط همین منبع معتبر است)
     */
    public function activeSubscribeQuery()
    {
        return $this->subscribes()
            ->where('status', 1)
            ->where('vip', '>', 0)
            ->whereNotNull('start_vip')
            ->whereNotNull('exp_vip')
            ->where('start_vip', '<=', now())
            ->where('exp_vip', '>=', now())
            ->orderByDesc('exp_vip');
    }

    /**
     * اگر بخوای فقط یکی رو بگیری (فعال‌ترین/دیرترین exp)
     */
    public function getActiveSubscribeAttribute(): ?Subscribe
    {
        return $this->activeSubscribeQuery()->first();
    }

    /**
     * Scope: users who had VIP subscribes before but currently have no active VIP.
     *
     * - Has at least one historic subscribe with `vip > 0`.
     * - Does NOT have any currently active subscribe (same logic as `activeSubscribeQuery`).
     */
    public function scopeWithExpiredVip($query)
    {
        return $query
            ->whereHas('subscribes', function ($q) {
                $q->where('vip', '>', 0);
            })
            ->whereDoesntHave('subscribes', function ($q) {
                $q->where('status', 1)
                  ->where('vip', '>', 0)
                  ->whereNotNull('start_vip')
                  ->whereNotNull('exp_vip')
                  ->where('start_vip', '<=', now())
                  ->where('exp_vip', '>=', now());
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * وضعیت VIP فعال (فقط از subscribes)
     */
    public function getHasActiveVipAttribute(): bool
    {
        return $this->activeSubscribeQuery()->exists();
    }

    /**
     * روزهای باقی‌مانده VIP (فقط از subscribes)
     * اگر فعال نبود => 0
     */
    public function getVipRemainingDaysAttribute(): int
    {
        $sub = $this->active_subscribe;

        if (!$sub || empty($sub->exp_vip)) {
            return 0;
        }

        $now = now();
        $exp = Carbon::parse($sub->exp_vip);

        if ($exp->lt($now)) {
            return 0;
        }

        // امروز را هم حساب کن (اگر 2 ساعت مانده، 1 روز)
        $days = (int) ceil($now->diffInSeconds($exp) / 86400);

        return max(0, $days);
    }

    /**
     * اطلاعات عضویت برای نمایش (فقط از subscribes)
     * - اگر هیچ رکوردی نبود: has_subscribe=false
     * - اگر رکورد بود ولی فعال نبود: active=false
     * - اگر فعال بود: active=true و remaining_days>0
     */
    public function getVipMembershipAttribute(): array
    {
        // آخرین رکورد (صرفاً برای نمایش وضعیت/تاریخچه)
        $latest = $this->subscribes()->orderByDesc('id')->first();

        if (!$latest) {
            return [
                'has_subscribe'     => false,
                'active'            => false,
                'vip'               => 0,
                'start_vip'         => null,
                'exp_vip'           => null,
                'status'            => null,
                'type'              => null,
                'method'            => null,
                'remaining_days'    => 0,
                'active_row'        => null,
            ];
        }

        $active = $this->active_subscribe;

        return [
            'has_subscribe'     => true,

            // رکورد آخر (ممکنه قدیمی/غیرفعال باشه، برای نمایش history خوبه)
            'vip'               => (int) ($latest->vip ?? 0),
            'start_vip'         => $latest->start_vip,
            'exp_vip'           => $latest->exp_vip,
            'status'            => isset($latest->status) ? (int)$latest->status : null,
            'type'              => isset($latest->type) ? (int)$latest->type : null,
            'method'            => isset($latest->method) ? (int)$latest->method : null,

            // نتیجه واقعی VIP فعال
            'active'            => (bool) $active,
            'remaining_days'    => $active ? $this->vip_remaining_days : 0,

            // اگر فعال وجود داشته باشد این ردیف را هم می‌دهیم
            'active_row'        => $active ? [
                'id'        => $active->id,
                'vip'       => (int) $active->vip,
                'start_vip' => $active->start_vip,
                'exp_vip'   => $active->exp_vip,
                'status'    => (int) $active->status,
                'type'      => isset($active->type) ? (int)$active->type : null,
                'method'    => isset($active->method) ? (int)$active->method : null,
            ] : null,
        ];
    }


    public function scopeWithActiveVip($query)
    {
        return $query->whereHas('subscribes', function($q){
            $q->where('status', 1)
            ->where('vip', '>', 0)
            ->whereNotNull('start_vip')
            ->whereNotNull('exp_vip')
            ->where('start_vip', '<=', now())
            ->where('exp_vip', '>=', now());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Other Relations
    |--------------------------------------------------------------------------
    */
    public function lbankApi()
    {
        return $this->hasOne(UserApiLbank::class, 'user_id');
    }

    public function remoteAccessTokens()
    {
        return $this->hasMany(RemoteAccessToken::class, 'user_id');
    }

    public function remoteDesktopAccess()
    {
        return $this->hasOne(RemoteAccessToken::class, 'user_id')
            ->where('service', config('remote_desktop.service', 'mt_terminal'));
    }


    /*
    |--------------------------------------------------------------------------
    | All notifications of user
    |--------------------------------------------------------------------------
    */
    public function notifs()
    {
        return $this->hasMany(Notifs::class, 'user_id', 'id');
    }




    public function balanceLogs()
    {
        return $this->hasMany(UserBalanceLog::class, 'user_id')->orderBy('time', 'desc');
    }

    public function latestBalanceLog()
    {
        return $this->hasOne(\App\Models\UserBalanceLog::class, 'user_id')->latest('time');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function ticketMessages()
    {
        return $this->hasMany(TicketMessage::class, 'user_id');
    }

    public function supportDepartments()
    {
        return $this->hasMany(SupportDepartmentAssignment::class, 'user_id');
    }

    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_user', 'user_id', 'user_group_id')
            ->using(UserGroupMember::class)
            ->withPivot(['joined_at', 'notes'])
            ->withTimestamps();
    }

    public function supportGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_support_accounts', 'support_user_id', 'user_group_id')
            ->using(UserGroupSupportAccount::class)
            ->withPivot(['assigned_by'])
            ->withTimestamps();
    }

    public function createdUserGroups()
    {
        return $this->hasMany(UserGroup::class, 'created_by');
    }

    public function wallet()
    {
        return $this->hasOne(UserWallet::class, 'user_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'user_id');
    }



    /*
    |--------------------------------------------------------------------------
    | Unread notifications (status = 0)
    |--------------------------------------------------------------------------
    */
    public function unreadNotifs()
    {
        return $this->hasMany(Notifs::class, 'user_id', 'id')
                    ->where('status', 0);
    }


    
    public function referrer()
    {
        return $this->belongsTo(self::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this->hasMany(self::class, 'referrer_id');
    }


    public function descendantsCountAll(): int
    {
        $sql = "
            WITH RECURSIVE t AS (
                SELECT id, referrer_id
                FROM users
                WHERE referrer_id = ?

                UNION ALL

                SELECT u.id, u.referrer_id
                FROM users u
                INNER JOIN t ON u.referrer_id = t.id
            )
            SELECT COUNT(*) AS cnt FROM t
        ";

        return (int) (DB::selectOne($sql, [$this->id])->cnt ?? 0);
    }

    public function activeDescendantsCountAllBySubscribe(): int
    {
        $sql = "
            WITH RECURSIVE t AS (
                SELECT id, referrer_id
                FROM users
                WHERE referrer_id = ?

                UNION ALL

                SELECT u.id, u.referrer_id
                FROM users u
                INNER JOIN t ON u.referrer_id = t.id
            )
            SELECT COUNT(DISTINCT s.user_id) AS cnt
            FROM subscribes s
            WHERE s.user_id IN (SELECT id FROM t)
            AND s.status = 1
            AND s.exp_vip IS NOT NULL
            AND s.exp_vip > NOW()
        ";

        return (int) (DB::selectOne($sql, [$this->id])->cnt ?? 0);
    }


    protected static function booted()
    {
        static::creating(function ($user) {

            if (empty($user->ref_code)) {

                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

                do {
                    $code = '';
                    for ($i = 0; $i < 6; $i++) {
                        $code .= $chars[random_int(0, strlen($chars) - 1)];
                    }
                } while (self::where('ref_code', $code)->exists());

                $user->ref_code = $code;
            }
        });

        static::created(function ($user) {
            UserWallet::query()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'toman_balance' => 0,
                    'usdt_balance' => 0,
                    'stars_balance' => 0,
                ]
            );
        });
    }


}
