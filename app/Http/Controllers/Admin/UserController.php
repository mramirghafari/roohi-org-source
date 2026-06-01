<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscribe;
use App\Models\Notifs;
use App\Models\ArchiveNotif;
use App\Models\Ticket;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use App\Models\UserGroup;
use App\Models\UserRole;
use App\Models\AuditLog;
use App\Models\SubscriptionTransaction;
use App\Models\PaymentRegistration;
use App\Models\RemoteAccessToken;
use App\Services\AuditLogger;
use App\Services\VipActivationSmsService;
use App\Services\WalletService;
use App\Jobs\SendArchivedNotificationsJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AllUsers = $this->visibleUsersQuery()->count();
        $ActiveUsers = $this->visibleUsersQuery()->withActiveVip()->count();
        $deactiveUsers = $this->visibleUsersQuery()->withExpiredVip()->count();
        $adminUsers = $this->currentUserIsAdmin() ? User::where('isAdmin', 1)->count() : 0;
        $isSalesTeamScope = !$this->currentUserIsAdmin();
        session()->put('backlink', route('users.index'));
        return view('dashboard.usersDashboard', compact('AllUsers', 'ActiveUsers', 'deactiveUsers', 'adminUsers', 'isSalesTeamScope'));
    }

    public function all()
    {
        session()->put('backlink', route('users.all'));
        return $this->usersListView('all');
    }

    public function activeUsers()
    {
        session()->put('backlink', route('users.activeUsers'));
        return $this->usersListView('active');
    }

    public function deactiveUsers()
    {
        session()->put('backlink', route('users.deactiveUsers'));
        return $this->usersListView('deactive');
    }

    public function leftUsers()
    {
        session()->put('backlink', route('users.leftUsers'));
        return $this->usersListView('left');
    }

    public function data(Request $request)
    {
        $scope = (string) $request->query('scope', 'all');
        $baseQuery = $this->usersScopedQuery($scope);
        $total = (clone $baseQuery)->count();
        $filteredQuery = $this->applyUsersDataSearch((clone $baseQuery), trim((string) data_get($request->input('search'), 'value', '')));
        $filtered = (clone $filteredQuery)->count();
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 100);
        $length = $length > 0 ? min($length, 100) : 100;
        $canBulkGroupAssign = $this->canBulkGroupAssign();

        $users = $filteredQuery
            ->with(['subscribes', 'latestBalanceLog'])
            ->latest('id')
            ->skip($start)
            ->take($length)
            ->get();

        $rows = $users->map(function (User $user, int $index) use ($start, $canBulkGroupAssign) {
            $activeSubscribe = $this->activeSubscribeFromLoadedUser($user);
            $remainingDays = $this->remainingDaysForSubscribe($activeSubscribe);
            $columns = [];

            if ($canBulkGroupAssign) {
                $columns[] = '<input type="checkbox" class="form-check-input bulk-user-checkbox" name="user_ids[]" value="' . e((string) $user->id) . '">';
            }

            $columns[] = '<bdi>' . number_format($start + $index + 1) . '</bdi>';
            $vipIcon = $activeSubscribe
                ? '<small class="badge bg-label-success me-1" style="padding: 5px"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg></small>'
                : '';

            $columns[] = '<div class="row flex-column"><div>' . $vipIcon . '<bdi>' . e((string) $user->nam) . '</bdi></div><div class="ps-5"><small>' . e((string) $user->mobile) . '</small></div></div>';
            $columns[] = e((string) $user->mobile);
            $columns[] = $activeSubscribe ? '<small>' . verta($activeSubscribe->start_vip)->format('Y-m-d') . '</small>' : '<small>-</small>';
            $columns[] = $activeSubscribe ? '<small>' . verta($activeSubscribe->exp_vip)->format('Y-m-d') . '</small>' : '<small>-</small>';
            $columns[] = $activeSubscribe
                ? '<small class="badge ' . ($remainingDays > 10 ? 'bg-label-info' : 'bg-label-danger') . '">' . $remainingDays . ' روز</small>'
                : '<small>-</small>';
            $columns[] = $user->lbank_uid ? '<span class="badge bg-label-success me-1 rounded p-1">' . e($user->lbank_uid) . '</span>' : '<span>-</span>';
            $columns[] = $user->coincall_uid ? '<span class="badge bg-label-info me-1 rounded p-1">' . e($user->coincall_uid) . '</span>' : '<span>-</span>';
            $columns[] = '<a href="' . route('users.detail', $user->id) . '" class="btn btn-sm btn-info">مشاهده</a>';

            return $columns;
        })->values();

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $rows,
        ]);
    }

    public function bulkAddToGroups(Request $request, AuditLogger $auditLogger)
    {
        if (!$this->canBulkGroupAssign()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'group_id' => ['required', 'integer', 'exists:user_groups,id'],
        ]);

        $userIds = $this->visibleUsersQuery()
            ->whereIn('id', array_values(array_unique(array_map('intval', $validated['user_ids']))))
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $groupId = $this->bulkAssignableGroups()
            ->where('id', (int) $validated['group_id'])
            ->value('id');

        if (empty($userIds) || !$groupId) {
            return back()->with('error', 'کاربر یا گروه معتبری برای عملیات دسته‌جمعی انتخاب نشده است.');
        }

        DB::transaction(function () use ($userIds, $groupId) {
            $syncMembers = [];
            foreach ($userIds as $userId) {
                $syncMembers[$userId] = [
                    'joined_at' => now(),
                    'notes' => 'افزودن دسته‌جمعی توسط مدیریت فروش',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            UserGroup::query()->findOrFail($groupId)->members()->syncWithoutDetaching($syncMembers);
        });

        $auditLogger->logAction(
            'users.bulk_added_to_groups',
            null,
            ['user_ids' => $userIds, 'group_id' => (int) $groupId],
            ['section' => 'users_bulk_groups', 'users_count' => count($userIds), 'groups_count' => 1],
            null,
            $request,
            'group_assignment'
        );

        return back()->with('success', count($userIds) . ' کاربر به گروه انتخاب‌شده اضافه شدند.');
    }

    public function userCamps()
    {
        $registrations = PaymentRegistration::query()
            ->where('status', PaymentRegistration::STATUS_SUCCESS)
            ->orderByDesc('paid_at')
            ->orderByDesc('id')
            ->get();

        session()->put('backlink', route('users.camps'));

        return view('dashboard.userCamps', compact('registrations'));
    }

    /* List users who had VIP subscribes but currently have no active VIP.
     * Returns JSON for quick inspection; change to a view if you prefer.
     */
    public function expired()
    {
        $users = User::with(['subscribes' => function ($q) {
            $q->where('vip', '>', 0)->orderByDesc('exp_vip');
        }])->withExpiredVip()->get();

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = $this->visibleUsersQuery()->with('roles')->findOrFail($id);
        $subscribes = $user->subscribes()->orderByDesc('id')->get();
        $balanceLogs = $user->balanceLogs()->orderByDesc('time')->get();
        $notifications = $user->notifs()->orderBy('created_at', 'desc')->get();
        $userWallet = UserWallet::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'toman_balance' => 0,
                'usdt_balance' => 0,
                'stars_balance' => 0,
            ]
        );
        $walletTransactions = WalletTransaction::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->limit(100)
            ->get();
        $allGroups = UserGroup::query()->where('is_active', 1)->orderBy('name')->get(['id', 'name']);
        $allRoles = UserRole::query()->where('is_active', true)->orderBy('name')->get();
        $userGroups = $user->userGroups()
            ->with(['supportAccounts.roles'])
            ->orderBy('name')
            ->get();
        $userGroupSupportAccounts = $userGroups
            ->flatMap(fn ($group) => $group->supportAccounts)
            ->unique('id')
            ->values();
        $userAuditLogs = AuditLog::query()
            ->with('user:id,nam,name,mobile,email')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere(function ($targetQuery) use ($user) {
                        $targetQuery->where('auditable_type', User::class)
                            ->where('auditable_id', $user->id);
                    });
            })
            ->latest('occurred_at')
            ->limit(150)
            ->get();
        $tickets = Ticket::query()
            ->with(['assignee:id,nam,name,last_name'])
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('messages', function ($messageQuery) use ($user) {
                        $messageQuery->where('user_id', $user->id);
                    });
            })
            ->orderByDesc('created_at')
            ->get()
            ->unique('id')
            ->values();
        $remoteDesktopAccess = RemoteAccessToken::query()
            ->where('user_id', $user->id)
            ->where('service', config('remote_desktop.service', 'mt_terminal'))
            ->latest('id')
            ->first();
        
        return view('dashboard.userInfo', compact('user', 'subscribes', 'balanceLogs','notifications', 'tickets', 'userWallet', 'walletTransactions', 'allGroups', 'allRoles', 'userGroups', 'userGroupSupportAccounts', 'userAuditLogs', 'remoteDesktopAccess'));
    }

    public function addUserToGroup(Request $request, User $user)
    {
        $validated = $request->validate([
            'group_id' => 'required|exists:user_groups,id',
        ]);

        $user->userGroups()->syncWithoutDetaching([
            $validated['group_id'] => [
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return back()->with('success', 'گروه کاربر با موفقیت بروزرسانی شد.');
    }

    public function removeUserFromGroup(User $user, UserGroup $group)
    {
        $user->userGroups()->detach($group->id);

        return back()->with('success', 'کاربر از گروه انتخاب‌شده حذف شد.');
    }

    public function updateWallet(Request $request, User $user, WalletService $walletService)
    {
        $validated = $request->validate([
            'operation' => 'required|in:deposit,withdraw',
            'asset' => 'required|in:toman,usdt,stars',
            'amount' => 'required|numeric|gt:0',
            'description' => 'nullable|string|max:500',
        ]);

        $admin = auth()->user();
        $description = trim((string) ($validated['description'] ?? ''));
        $description = $description !== '' ? $description : null;

        if ($validated['operation'] === 'deposit') {
            $walletService->deposit(
                $user,
                $validated['asset'],
                (float) $validated['amount'],
                $description ?? 'واریز توسط مدیریت',
                $admin,
            );
        } else {
            $walletService->withdraw(
                $user,
                $validated['asset'],
                (float) $validated['amount'],
                $description ?? 'کسر توسط مدیریت',
                $admin,
            );
        }

        $assetLabels = [
            'toman' => 'تومان',
            'usdt' => 'تتر',
            'stars' => 'STARS',
        ];

        $operationLabel = $validated['operation'] === 'deposit' ? 'واریز' : 'کسر';
        $assetLabel = $assetLabels[$validated['asset']] ?? $validated['asset'];

        Notifs::query()->create([
            'user_id' => $user->id,
            'archive_notif_id' => null,
            'title' => 'تراکنش کیف پول توسط مدیریت',
            'content' => "{$operationLabel} {$validated['amount']} {$assetLabel} توسط مدیریت انجام شد." . ($description ? "\nتوضیحات: {$description}" : ''),
            'status' => 0,
            'sms' => 0,
        ]);

        return back()->with('success', 'تراکنش کیف پول کاربر با موفقیت ثبت شد.');
    }

    public function updatePermission(Request $request, User $user, AuditLogger $auditLogger)
    {
        $validated = $request->validate([
            'access_role' => 'required|in:super_admin,support_admin,normal_user',
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['integer', 'exists:user_roles,id'],
        ]);

        $user->load('roles');
        $before = $this->permissionSnapshot($user);

        if ($validated['access_role'] === 'super_admin') {
            $user->update([
                'isAdmin' => 1,
                'is_support' => 0,
            ]);
        } elseif ($validated['access_role'] === 'support_admin') {
            $user->update([
                'isAdmin' => 0,
                'is_support' => 1,
            ]);
        } else {
            $user->update([
                'isAdmin' => 0,
                'is_support' => 0,
            ]);
        }

        $syncData = [];
        foreach (array_values(array_unique(array_map('intval', $validated['role_ids'] ?? []))) as $roleId) {
            $syncData[$roleId] = [
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $user->roles()->sync($syncData);
        $user->load('roles');

        $auditLogger->logAction(
            'user.permissions_updated',
            $user,
            ['before' => $before, 'after' => $this->permissionSnapshot($user)],
            [
                'section' => 'user_profile_permissions',
                'assigned_role_ids' => array_keys($syncData),
            ],
            null,
            $request,
            'role_assignment'
        );

        return redirect()->back()->with('success', 'سطح دسترسی و نقش‌های کاربر با موفقیت بروزرسانی شد.');
    }

    private function permissionSnapshot(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->nam ?: $user->name,
            'mobile' => $user->mobile,
            'is_admin' => (bool) $user->isAdmin,
            'is_support' => (bool) $user->is_support,
            'roles' => $user->roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'permissions' => $role->permissions ?? [],
            ])->values()->all(),
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $User)
    {
       // dd($request->all());

        $subscribe = Subscribe::where('user_id', $User->id)->where('status', 1)->where('vip', '>', 0)->first();
        
        // Case 1: vip = '000' - disable all subscriptions
        if($request->vip == '000'){
            Subscribe::where('user_id', $User->id)->update(['status' => 0]);
            return redirect()->back()->with('error', 'اشتراک کاربر به صورت کامل غیرفعال شد!');
        }
        
        // Case 2: Only addday is sent (no vip field) - extend current subscription
        if($request->addday > 0) {
            if($subscribe) {
                $subscribe->exp_vip = Carbon::parse($subscribe->exp_vip)->addDays(intval($request->addday));
                $subscribe->save();

                $this->createAdminSubscriptionLog(
                    userId: (int) $User->id,
                    subscribeId: (int) $subscribe->id,
                    planMonths: 0,
                    adminUserId: (int) auth()->id(),
                    activatedAt: now(),
                    days: intval($request->addday),
                    smsTemplate: 'addDaysVIP',
                    message: 'تمدید دستی ادمین: ' . intval($request->addday) . ' روز'
                );

                return redirect()->back()->with('success', 'اشتراک کاربر با موفقیت بروزرسانی شد.');
            }
        }
        
        // Case 3: vip is sent - disable current subscription and create new one
        if($subscribe) {
            Subscribe::where('user_id', $User->id)->update(['status' => 0]);
        }
        
        $newSubscribe = new Subscribe();
        $newSubscribe->user_id = $User->id;
        $newSubscribe->type = 2; // Add by admin
        $newSubscribe->status = 1;
        $newSubscribe->start_vip = Carbon::now();
        $newSubscribe->exp_vip = Carbon::now()->addDays(intval($request->vip) ?: intval($request->addday));
        $newSubscribe->vip = $request->vip ?: $request->addday;
        $newSubscribe->register_date = Carbon::now();
        $newSubscribe->method = 0;
        $newSubscribe->save();

        $days = intval($request->vip) ?: intval($request->addday);
        $planMonths = in_array($days, [30, 60, 90], true) ? intval($days / 30) : 0;

        $this->createAdminSubscriptionLog(
            userId: (int) $User->id,
            subscribeId: (int) $newSubscribe->id,
            planMonths: $planMonths,
            adminUserId: (int) auth()->id(),
            activatedAt: now(),
            days: $days,
            smsTemplate: 'activeVIP',
            message: 'ثبت دستی ادمین: ایجاد اشتراک ' . $days . ' روزه'
        );

        return redirect()->back()->with('success', 'اشتراک کاربر با موفقیت بروزرسانی شد.');
    }

    private function createAdminSubscriptionLog(
        int $userId,
        int $subscribeId,
        int $planMonths,
        int $adminUserId,
        Carbon $activatedAt,
        int $days = 0,
        string $smsTemplate = 'activeVIP',
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

        $smsService = app(VipActivationSmsService::class);
        if ($smsTemplate === 'addDaysVIP') {
            $smsService->sendAddDaysByUserId($userId, $days);
            return;
        }

        $smsService->sendActiveVipByUserId($userId, $days);
    }

    public function removeUserUid(Request $request, User $user)
    {
        
        $user->lbank_uid = null;
        $user->save();

        return redirect()->back()->with('success', 'UID کاربر با موفقیت حذف شد.');
    }

    public function getDemoVIP(Request $request)
    {
        $request->validate([
            'lbank_uid' => ['required', 'string', 'max:40'],
        ]);

        $user = auth()->user();
        $uid = trim((string) $request->input('lbank_uid'));

        $uidExistsForAnotherUser = User::query()
            ->where('lbank_uid', $uid)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($uidExistsForAnotherUser) {
            return back()->withErrors([
                'lbank_uid' => 'این UID قبلا توسط کاربر دیگری ثبت شده است.',
            ])->withInput();
        }

        $hasDemoSub = Subscribe::query()
            ->where('user_id', $user->id)
            ->where('type', 4)
            ->exists();

        if ($hasDemoSub) {
            return redirect()->route('subscription')->withErrors([
                'error' => 'شما قبلا از اشتراک دمو استفاده کرده‌اید.',
            ]);
        }

        $url = 'https://roohi.trade/newbot/test_user_info.php';

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->get($url, [
                    'mch' => $uid,
                ]);
        } catch (\Throwable $e) {
            return back()->withErrors([
                'error' => 'خطا در اتصال به سرور ال بانک',
            ])->withInput();
        }

        if (!$response->ok()) {
            return back()->withErrors([
                'error' => 'پاسخ نامعتبر از ال بانک',
            ])->withInput();
        }

        $json = $response->json();

        if (
            !isset($json['success']) ||
            !isset($json['inTeam']) ||
            !isset($json['data'])
        ) {
            return back()->withErrors([
                'error' => 'ساختار پاسخ ال بانک نامعتبر است',
            ])->withInput();
        }

        $success = (bool) $json['success'];
        $inTeam = (bool) $json['inTeam'];
        $deposit = (bool) ($json['data']['deposit'] ?? false);
        $total = (float) ($json['data']['assets']['total'] ?? 0);

        if (!$success || !$inTeam) {
            return back()->withErrors([
                'error' => 'این UID در تیم روحی ترید نیست یا صحیح نمیباشد.',
            ])->withInput();
        }

        if (!$deposit) {
            return back()->withErrors([
                'error' => 'برای فعال‌سازی دمو، حساب شما باید دیپازیت داشته باشد.',
            ])->withInput();
        }

        if ($total < 100) {
            return back()->withErrors([
                'error' => 'حداقل مجموع دارایی برای دریافت دمو باید 100 دلار باشد.',
            ])->withInput();
        }

        $now = now();

        $user->lbank_uid = $uid;
        $user->save();

        Subscribe::query()->create([
            'user_id' => $user->id,
            'vip' => 1,
            'start_vip' => $now,
            'exp_vip' => $now->copy()->addDays(30),
            'type' => 4,
            'register_date' => $now,
            'method' => 4,
            'status' => 1,
        ]);

        return redirect()->route('subscription')->with('demo_vip_success', true);
    }

    public function updateAdminNote(Request $request, User $user)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:5000',
        ]);

        $user->update([
            'admin_note' => $request->admin_note,
        ]);

        return redirect()->back()->with('success', 'یادداشت کاربر با موفقیت بروزرسانی شد.');
    }

    public function search(Request $request) {

        $isSalesTeamScope = !$this->currentUserIsAdmin();

        if($request->has('user')) {
            $search = $request->input('user');
            $user = $request->user;
           
            $Users = $this->visibleUsersQuery()
                ->where(function (Builder $query) use ($user) {
                    $query->where('username', $user)
                        ->orWhere('mobile', $user)
                        ->orWhere('name', 'like', '%' . $user . '%')
                        ->orWhere('last_name', 'like', '%' . $user . '%')
                        ->orWhere('nam', 'like', '%' . $user . '%');
                })
                ->get();

           
        }else {
            $Users = null;
            $search = null;
            $Bot = null;
        }    

        return view('dashboard.userSearch',compact('Users','search', 'isSalesTeamScope'));
    }

    private function currentUserIsAdmin(): bool
    {
        return (int) auth()->user()->isAdmin === 1;
    }

    private function canSeeAllSalesUsers(): bool
    {
        $user = auth()->user();

        return (int) $user->isAdmin === 1
            || $user->hasRole('sales-manager')
            || $user->hasRole('sales-expert');
    }

    private function canBulkGroupAssign(): bool
    {
        return $this->canSeeAllSalesUsers();
    }

    private function bulkAssignableGroups()
    {
        return UserGroup::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function usersListView(string $scope)
    {
        $isSalesTeamScope = !$this->currentUserIsAdmin();
        $bulkGroups = $this->bulkAssignableGroups();
        $canBulkGroupAssign = $this->canBulkGroupAssign();
        $usersAjaxUrl = route('users.data', ['scope' => $scope]);
        $exportScope = $scope;

        return view('dashboard.users', compact('isSalesTeamScope', 'bulkGroups', 'canBulkGroupAssign', 'usersAjaxUrl', 'exportScope'));
    }

    private function usersScopedQuery(string $scope): Builder
    {
        $query = $this->visibleUsersQuery();

        if ($scope === 'active') {
            return $query->withActiveVip();
        }

        if ($scope === 'deactive') {
            return $query->doesntHave('subscribes');
        }

        if ($scope === 'left') {
            return $query->withExpiredVip();
        }

        return $query;
    }

    private function applyUsersDataSearch(Builder $query, string $search): Builder
    {
        if ($search === '') {
            return $query;
        }

        return $query->where(function (Builder $searchQuery) use ($search) {
            $searchQuery->where('nam', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('last_name', 'like', '%' . $search . '%')
                ->orWhere('username', 'like', '%' . $search . '%')
                ->orWhere('mobile', 'like', '%' . $search . '%')
                ->orWhere('lbank_uid', 'like', '%' . $search . '%')
                ->orWhere('coincall_uid', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        });
    }

    private function activeSubscribeFromLoadedUser(User $user): ?Subscribe
    {
        return $user->subscribes
            ->filter(function ($subscribe) {
                return (int) ($subscribe->status ?? 0) === 1
                    && (int) ($subscribe->vip ?? 0) > 0
                    && !empty($subscribe->start_vip)
                    && !empty($subscribe->exp_vip)
                    && $subscribe->start_vip <= now()
                    && $subscribe->exp_vip >= now();
            })
            ->sortByDesc('exp_vip')
            ->first();
    }

    private function remainingDaysForSubscribe(?Subscribe $subscribe): int
    {
        if (!$subscribe || !$subscribe->exp_vip) {
            return 0;
        }

        return max(0, (int) ceil(now()->diffInSeconds($subscribe->exp_vip, false) / 86400));
    }

    private function visibleUsersQuery(): Builder
    {
        $query = User::query();

        if ($this->canSeeAllSalesUsers()) {
            return $query;
        }

        $managerId = (int) auth()->id();

        return $query->whereHas('userGroups.supportAccounts', function (Builder $supportQuery) use ($managerId) {
            $supportQuery->where('users.id', $managerId);
        });
    }

    
    public function sendNotif()
    {
        $ArchiveNotifs = ArchiveNotif::orderByDesc('id')->get();
        return view('dashboard.sendNotif', compact('ArchiveNotifs'));
    }

    public function sendNotifProcess(Request $request) 
    {
        $ArchiveNotif = new ArchiveNotif();
        $ArchiveNotif->title = $request->title;
        $ArchiveNotif->usersGroup = $request->send_to;
        $ArchiveNotif->content = $request->content;
        $ArchiveNotif->sms = $request->sms ? 1 : 0;
        $ArchiveNotif->created_by = auth()->user()->id;
        $ArchiveNotif->status = 0; // تاریخچه، هنوز ارسال نشده
        $ArchiveNotif->save();

        // Job را برای ارسال فوری Dispatch کن (اختیاری)
        // \App\Jobs\SendArchivedNotificationsJob::dispatch();

        return redirect()->route('users.sendNotif')
            ->with('success', 'اعلان برای گروه کاربری مورد نظر ثبت شد و به زودی ارسال خواهد شد.');
    }

    public function editNotif(ArchiveNotif $notif) {


        return view('dashboard.notifEdit',compact('notif'));

    }
    public function NotifUpdate(Request $request, ArchiveNotif $notif)
    {

        
        if (!$notif) {
            return redirect()->back()->with('error', 'آرشیو نوتیف پیدا نشد.');
        }

        $notif->title = $request->title;
        $notif->content = $request->content;
        $notif->save();

        // اگر قبلا نوتیف‌هایی از این آرشیو ارسال شده‌اند، عنوان و محتوا را به‌روزرسانی کن
        Notifs::where('archive_notif_id', $notif->id)
            ->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);

        return redirect()->back()->with('success', 'اعلان و پیام های ارسالی با موفقیت به‌روزرسانی شدند.');
    }

    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArchiveNotif $notif)
    {
        DB::beginTransaction();
        try {
            // حذف تمام نوتیف‌هایی که به این آرشیو مربوط هستند
            Notifs::where('archive_notif_id', $notif->id)->delete();

            // حذف آرشیو (SoftDeletes خواهد بود)
            $notif->delete();

            DB::commit();
            return redirect()->back()->with('success', 'آرشیو نوتیف و نوتیف‌های مرتبط با موفقیت حذف شدند.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطا در حذف: ' . $e->getMessage());
        }
    }
}
