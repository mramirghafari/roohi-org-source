<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Models\SubscriptionTransaction;
use App\Services\VipActivationSmsService;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserGroupCommissionRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserGroupController extends Controller
{
    public function index()
    {
        $groups = UserGroup::query()
            ->withCount(['members', 'supportAccounts'])
            ->latest('id')
            ->get();

        session()->put('backlink', route('user-groups.index'));

        return view('dashboard.user-groups.index', compact('groups'));
    }

    public function create()
    {
        $assignmentRoles = UserGroup::ASSIGNMENT_ROLES;
        $assignmentUsers = $this->assignmentUserOptions();
        $supportUsers = $assignmentUsers[UserGroup::ASSIGNMENT_TECHNICAL_SUPPORT];

        $users = User::query()
            ->select('id', 'nam', 'mobile')
            ->orderByDesc('id')
            ->limit(2000)
            ->get();

        return view('dashboard.user-groups.create', compact('supportUsers', 'users', 'assignmentRoles', 'assignmentUsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'commission_mode' => 'nullable|in:inherit,custom',
            'support_user_ids' => 'nullable|array',
            'support_user_ids.*' => 'integer|exists:users,id',
            'assignment_user_ids' => 'nullable|array',
            'assignment_user_ids.*' => 'nullable|integer|exists:users,id',
            'member_user_ids' => 'nullable|array',
            'member_user_ids.*' => 'integer|exists:users,id',
            'rules' => 'nullable|array',
            'rules.*.event' => 'nullable|in:referral_register,referral_vip_purchase',
            'rules.*.level' => 'nullable|integer|min:1|max:100',
            'rules.*.stars_reward' => 'nullable|numeric|min:0',
            'rules.*.toman_reward' => 'nullable|numeric|min:0',
            'rules.*.usdt_reward' => 'nullable|numeric|min:0',
            'rules.*.commission_percent' => 'nullable|numeric|min:0|max:100',
            'rules.*.is_active' => 'nullable|in:0,1',
        ]);

        DB::transaction(function () use ($validated) {
            $group = UserGroup::query()->create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . now()->format('His'),
                'description' => $validated['description'] ?? null,
                'created_by' => auth()->id(),
                'is_active' => true,
                'commission_mode' => $validated['commission_mode'] ?? UserGroup::COMMISSION_MODE_INHERIT,
                'attraction_stars_reward' => 0,
                'purchase_commission_percent' => 0,
                'purchase_reward_mode' => UserGroup::PURCHASE_REWARD_NONE,
                'purchase_reward_toman' => 0,
                'purchase_reward_usdt' => 0,
            ]);

            $supportIds = $this->filterSupportUserIds($validated['support_user_ids'] ?? []);
            if (!empty($supportIds)) {
                $syncData = [];
                foreach ($supportIds as $supportId) {
                    $syncData[$supportId] = [
                        'assigned_by' => auth()->id(),
                        'assignment_role' => UserGroup::ASSIGNMENT_TECHNICAL_SUPPORT,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $group->supportAccounts()->sync($syncData);
            }

            $this->syncGroupAssignments($group, $validated['assignment_user_ids'] ?? []);

            $memberIds = array_values(array_unique(array_map('intval', $validated['member_user_ids'] ?? [])));
            if (!empty($memberIds)) {
                $syncMembers = [];
                foreach ($memberIds as $memberId) {
                    $syncMembers[$memberId] = [
                        'joined_at' => now(),
                        'notes' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $group->members()->sync($syncMembers);
            }

            if (($validated['commission_mode'] ?? UserGroup::COMMISSION_MODE_INHERIT) === UserGroup::COMMISSION_MODE_CUSTOM) {
                $preparedRules = $this->prepareCommissionRows($group->id, $validated['rules'] ?? []);
                if (!empty($preparedRules)) {
                    UserGroupCommissionRule::query()->insert(array_values($preparedRules));
                }
            }
        });

        return redirect()->route('user-groups.index')->with('success', 'گروه جدید با موفقیت ایجاد شد.');
    }

    public function show(UserGroup $group)
    {
        $group->load([
            'members:id,nam,mobile',
            'supportAccounts:id,nam,mobile,is_support,isAdmin',
            'supportAccounts.roles',
            'commissionRules' => function ($query) {
                $query->orderBy('event')->orderBy('level');
            },
        ]);

        $assignmentRoles = UserGroup::ASSIGNMENT_ROLES;
        $assignmentUsers = $this->assignmentUserOptions();
        $groupAssignmentIds = [];
        foreach (array_keys($assignmentRoles) as $assignmentRole) {
            $groupAssignmentIds[$assignmentRole] = optional($group->supportAccounts->first(
                fn ($support) => $support->pivot?->assignment_role === $assignmentRole
            ))->id;
        }

        $allUsers = User::query()
            ->select('id', 'nam', 'mobile', 'is_support', 'isAdmin')
            ->orderByDesc('id')
            ->limit(2000)
            ->get();

        return view('dashboard.user-groups.show', compact('group', 'allUsers', 'assignmentRoles', 'assignmentUsers', 'groupAssignmentIds'));
    }

    public function update(Request $request, UserGroup $group)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
            'commission_mode' => 'required|in:inherit,custom',
            'attraction_stars_reward' => 'required|numeric|min:0',
            'purchase_commission_percent' => 'required|numeric|min:0|max:100',
            'purchase_reward_mode' => 'required|in:none,toman,usdt,both',
            'purchase_reward_toman' => 'required|numeric|min:0',
            'purchase_reward_usdt' => 'required|numeric|min:0',
            'subscription_operation' => 'nullable|in:none,add_days,set_new',
            'subscription_days' => 'nullable|integer|min:1|max:3650',
            'support_user_ids' => 'nullable|array',
            'support_user_ids.*' => 'integer|exists:users,id',
            'assignment_user_ids' => 'nullable|array',
            'assignment_user_ids.*' => 'nullable|integer|exists:users,id',
        ]);

        DB::transaction(function () use ($group, $validated) {
            $group->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => (bool) ($validated['is_active'] ?? false),
                'commission_mode' => $validated['commission_mode'],
                'attraction_stars_reward' => $validated['attraction_stars_reward'],
                'purchase_commission_percent' => $validated['purchase_commission_percent'],
                'purchase_reward_mode' => $validated['purchase_reward_mode'],
                'purchase_reward_toman' => $validated['purchase_reward_toman'],
                'purchase_reward_usdt' => $validated['purchase_reward_usdt'],
            ]);

            if ($validated['commission_mode'] === UserGroup::COMMISSION_MODE_INHERIT) {
                $group->commissionRules()->delete();
            }

            if (array_key_exists('support_user_ids', $validated)) {
                $supportIds = $this->filterSupportUserIds($validated['support_user_ids'] ?? []);
                $syncData = [];
                foreach ($supportIds as $supportId) {
                    $syncData[$supportId] = [
                        'assigned_by' => auth()->id(),
                        'assignment_role' => UserGroup::ASSIGNMENT_TECHNICAL_SUPPORT,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                $group->supportAccounts()->sync($syncData);
            }

            $this->syncGroupAssignments($group, $validated['assignment_user_ids'] ?? []);

            $mode = $validated['subscription_operation'] ?? 'none';
            $days = (int) ($validated['subscription_days'] ?? 0);

            if ($mode !== 'none' && $days > 0) {
                $memberIds = $group->members()->pluck('users.id')->map(fn ($id) => (int) $id)->all();
                $this->applySubscriptionOperation($memberIds, $mode, $days);
            }
        });

        return redirect()->route('user-groups.show', $group)->with('success', 'تنظیمات گروه با موفقیت ذخیره شد.');
    }

    public function updateCommissions(Request $request, UserGroup $group)
    {
        if ($group->commission_mode !== UserGroup::COMMISSION_MODE_CUSTOM) {
            return redirect()->route('user-groups.show', $group)->with('error', 'این گروه روی حالت ارث‌بری از تنظیمات پیش‌فرض است.');
        }

        $validated = $request->validate([
            'rules' => 'nullable|array',
            'rules.*.event' => 'nullable|in:referral_register,referral_vip_purchase',
            'rules.*.level' => 'nullable|integer|min:1|max:100',
            'rules.*.stars_reward' => 'nullable|numeric|min:0',
            'rules.*.toman_reward' => 'nullable|numeric|min:0',
            'rules.*.usdt_reward' => 'nullable|numeric|min:0',
            'rules.*.commission_percent' => 'nullable|numeric|min:0|max:100',
            'rules.*.is_active' => 'nullable|in:0,1',
        ]);

        $prepared = $this->prepareCommissionRows($group->id, $validated['rules'] ?? []);

        DB::transaction(function () use ($group, $prepared) {
            $group->commissionRules()->delete();

            if (!empty($prepared)) {
                UserGroupCommissionRule::query()->insert(array_values($prepared));
            }
        });

        return redirect()->route('user-groups.show', $group)->with('success', 'قوانین پورسانت لول‌بندی‌شده ذخیره شد.');
    }

    public function addMember(Request $request, UserGroup $group)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:255',
        ]);

        $group->members()->syncWithoutDetaching([
            $validated['user_id'] => [
                'joined_at' => now(),
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return redirect()->route('user-groups.show', $group)->with('success', 'کاربر با موفقیت به گروه اضافه شد.');
    }

    public function removeMember(UserGroup $group, User $user)
    {
        $group->members()->detach($user->id);

        return redirect()->route('user-groups.show', $group)->with('success', 'کاربر از گروه حذف شد.');
    }

    public function addSupport(Request $request, UserGroup $group)
    {
        $validated = $request->validate([
            'support_user_id' => 'required|exists:users,id',
            'assignment_role' => 'nullable|in:' . implode(',', array_keys(UserGroup::ASSIGNMENT_ROLES)),
        ]);

        $assignmentRole = $validated['assignment_role'] ?? UserGroup::ASSIGNMENT_TECHNICAL_SUPPORT;

        $supportUser = User::query()->findOrFail($validated['support_user_id']);
        if (!$this->userCanFillAssignmentRole($supportUser, $assignmentRole)) {
            return redirect()->route('user-groups.show', $group)->with('error', 'این کاربر نقش لازم برای این جایگاه گروه را ندارد.');
        }

        $group->supportAccounts()->syncWithoutDetaching([
            $validated['support_user_id'] => [
                'assigned_by' => auth()->id(),
                'assignment_role' => $assignmentRole,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return redirect()->route('user-groups.show', $group)->with('success', 'اکانت پشتیبان با موفقیت اساین شد.');
    }

    public function removeSupport(UserGroup $group, User $user)
    {
        $group->supportAccounts()->detach($user->id);

        return redirect()->route('user-groups.show', $group)->with('success', 'اکانت پشتیبان از گروه حذف شد.');
    }

    private function filterSupportUserIds(array $userIds): array
    {
        $ids = array_values(array_unique(array_map('intval', $userIds)));

        if (empty($ids)) {
            return [];
        }

        return User::query()
            ->whereIn('id', $ids)
            ->where(function ($query) {
                $query->where('is_support', 1)->orWhere('isAdmin', 1);
            })
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function assignmentUserOptions(): array
    {
        $options = [];

        foreach (array_keys(UserGroup::ASSIGNMENT_ROLES) as $assignmentRole) {
            $options[$assignmentRole] = $this->usersForAssignmentRole($assignmentRole)->get();
        }

        return $options;
    }

    private function usersForAssignmentRole(string $assignmentRole)
    {
        return User::query()
            ->select('id', 'nam', 'name', 'mobile', 'is_support', 'isAdmin')
            ->with('roles:id,name,slug')
            ->where(function ($query) use ($assignmentRole) {
                match ($assignmentRole) {
                    UserGroup::ASSIGNMENT_SALES_SUPPORT => $query->whereHas('roles', fn ($roleQuery) => $roleQuery->where('slug', 'marketer')),
                    UserGroup::ASSIGNMENT_TECHNICAL_SUPPORT => $query->where('is_support', 1)
                        ->orWhere('isAdmin', 1)
                        ->orWhereHas('roles', fn ($roleQuery) => $roleQuery->where('slug', 'support-manager')),
                    UserGroup::ASSIGNMENT_SALES_EXPERT => $query->whereHas('roles', fn ($roleQuery) => $roleQuery->where('slug', 'sales-expert')),
                    UserGroup::ASSIGNMENT_SALES_MANAGER => $query->where('isAdmin', 1)
                        ->orWhereHas('roles', fn ($roleQuery) => $roleQuery->where('slug', 'sales-manager')),
                    default => $query->whereRaw('1 = 0'),
                };
            })
            ->orderByDesc('id')
            ->limit(1000);
    }

    private function userCanFillAssignmentRole(User $user, string $assignmentRole): bool
    {
        return $this->usersForAssignmentRole($assignmentRole)
            ->where('id', $user->id)
            ->exists();
    }

    private function syncGroupAssignments(UserGroup $group, array $assignmentUserIds): void
    {
        $rows = [];
        $now = now();

        foreach (array_keys(UserGroup::ASSIGNMENT_ROLES) as $assignmentRole) {
            $userId = (int) ($assignmentUserIds[$assignmentRole] ?? 0);
            if ($userId <= 0) {
                continue;
            }

            $user = User::query()->find($userId);
            if (!$user || !$this->userCanFillAssignmentRole($user, $assignmentRole)) {
                continue;
            }

            $rows[] = [
                'user_group_id' => $group->id,
                'support_user_id' => $userId,
                'assigned_by' => auth()->id(),
                'assignment_role' => $assignmentRole,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('user_group_support_accounts')
            ->where('user_group_id', $group->id)
            ->whereIn('assignment_role', array_keys(UserGroup::ASSIGNMENT_ROLES))
            ->delete();

        if (!empty($rows)) {
            DB::table('user_group_support_accounts')->insert($rows);
        }
    }

    private function applySubscriptionOperation(array $memberIds, string $mode, int $days): void
    {
        if (empty($memberIds) || $days <= 0) {
            return;
        }

        foreach ($memberIds as $memberId) {
            $activeSubscribe = Subscribe::query()
                ->where('user_id', $memberId)
                ->where('status', 1)
                ->where('vip', '>', 0)
                ->orderByDesc('id')
                ->first();

            if ($mode === 'add_days') {
                if ($activeSubscribe) {
                    $activeSubscribe->exp_vip = Carbon::parse($activeSubscribe->exp_vip)->addDays($days);
                    $activeSubscribe->save();

                    $this->createAdminSubscriptionLog(
                        userId: (int) $memberId,
                        subscribeId: (int) $activeSubscribe->id,
                        planMonths: 0,
                        adminUserId: (int) auth()->id(),
                        activatedAt: now(),
                        days: $days,
                        message: 'تمدید گروهی ادمین: ' . $days . ' روز'
                    );

                    continue;
                }

                $subscribe = Subscribe::query()->create([
                    'user_id' => $memberId,
                    'type' => 2,
                    'status' => 1,
                    'start_vip' => Carbon::now(),
                    'exp_vip' => Carbon::now()->addDays($days),
                    'vip' => $days,
                    'register_date' => Carbon::now(),
                    'method' => 0,
                ]);

                $planMonths = in_array($days, [30, 60, 90], true) ? intval($days / 30) : 0;

                $this->createAdminSubscriptionLog(
                    userId: (int) $memberId,
                    subscribeId: (int) $subscribe->id,
                    planMonths: $planMonths,
                    adminUserId: (int) auth()->id(),
                    activatedAt: now(),
                    days: $days,
                    message: 'ثبت گروهی ادمین: ایجاد اشتراک ' . $days . ' روزه'
                );

                continue;
            }

            if ($mode === 'set_new') {
                Subscribe::query()
                    ->where('user_id', $memberId)
                    ->where('status', 1)
                    ->update(['status' => 0]);

                $subscribe = Subscribe::query()->create([
                    'user_id' => $memberId,
                    'type' => 2,
                    'status' => 1,
                    'start_vip' => Carbon::now(),
                    'exp_vip' => Carbon::now()->addDays($days),
                    'vip' => $days,
                    'register_date' => Carbon::now(),
                    'method' => 0,
                ]);

                $planMonths = in_array($days, [30, 60, 90], true) ? intval($days / 30) : 0;

                $this->createAdminSubscriptionLog(
                    userId: (int) $memberId,
                    subscribeId: (int) $subscribe->id,
                    planMonths: $planMonths,
                    adminUserId: (int) auth()->id(),
                    activatedAt: now(),
                    days: $days,
                    message: 'ثبت گروهی ادمین: جایگزینی با اشتراک ' . $days . ' روزه'
                );
            }
        }
    }

    private function createAdminSubscriptionLog(
        int $userId,
        int $subscribeId,
        int $planMonths,
        int $adminUserId,
        Carbon $activatedAt,
        int $days = 0,
        string $message = ''
    ): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('subscription_transactions')) {
            return;
        }

        $tx = SubscriptionTransaction::query()->create([
            'user_id' => $userId,
            'admin_user_id' => $adminUserId,
            'subscribe_id' => $subscribeId,
            'plan_months' => $planMonths,
            'amount' => 0,
            'currency' => 'IRT',
            'status' => SubscriptionTransaction::STATUS_SUCCESS,
            'gateway_status' => 'ADMIN',
            'gateway_code' => 0,
            'message' => $message,
            'activated_at' => $activatedAt,
            'paid_at' => now(),
        ]);

        $tx->update([
            'ref_id' => 'ADM-' . $tx->id,
        ]);

        app(VipActivationSmsService::class)->sendByUserId($userId, $days);
    }

    private function prepareCommissionRows(int $groupId, array $rows): array
    {
        $prepared = [];

        foreach ($rows as $row) {
            $event = $row['event'] ?? null;
            $level = isset($row['level']) ? (int) $row['level'] : 0;

            if (!$event || $level < 1) {
                continue;
            }

            $key = $event . ':' . $level;
            $prepared[$key] = [
                'user_group_id' => $groupId,
                'event' => $event,
                'level' => $level,
                'stars_reward' => (float) ($row['stars_reward'] ?? 0),
                'toman_reward' => (float) ($row['toman_reward'] ?? 0),
                'usdt_reward' => (float) ($row['usdt_reward'] ?? 0),
                'commission_percent' => (float) ($row['commission_percent'] ?? 0),
                'is_active' => ((int) ($row['is_active'] ?? 1)) === 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $prepared;
    }
}
