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
use App\Models\SubscriptionTransaction;
use App\Models\PaymentRegistration;
use App\Models\RemoteAccessToken;
use App\Services\VipActivationSmsService;
use App\Services\WalletService;
use App\Jobs\SendArchivedNotificationsJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AllUsers = User::count();
        $ActiveUsers = User::withActiveVip()->count();
        $deactiveUsers = User::withExpiredVip()->count();
        $adminUsers = User::where('isAdmin', 1)->count();
        session()->put('backlink', route('users.index'));
        return view('dashboard.usersDashboard', compact('AllUsers', 'ActiveUsers', 'deactiveUsers', 'adminUsers'));
    }

    public function all()
    {
        $Users = User::all();
        session()->put('backlink', route('users.all'));
        return view('dashboard.users', compact('Users'));
    }

    public function activeUsers()
    {
        $Users = User::withActiveVip()->get();
        session()->put('backlink', route('users.activeUsers'));
        return view('dashboard.users', compact('Users'));
    }

    public function deactiveUsers()
    {
        $Users = User::doesntHave('subscribes')->get();
        session()->put('backlink', route('users.deactiveUsers'));
        return view('dashboard.users', compact('Users'));
    }

    public function leftUsers()
    {
        $Users = User::withExpiredVip()->get();
        session()->put('backlink', route('users.leftUsers'));
        return view('dashboard.users', compact('Users'));
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
        $user = User::findOrFail($id);
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
        $userGroups = $user->userGroups()->orderBy('name')->get();
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
        
        return view('dashboard.userInfo', compact('user', 'subscribes', 'balanceLogs','notifications', 'tickets', 'userWallet', 'walletTransactions', 'allGroups', 'userGroups', 'remoteDesktopAccess'));
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

    public function updatePermission(Request $request, User $user)
    {
        $validated = $request->validate([
            'access_role' => 'required|in:super_admin,support_admin,normal_user',
        ]);

        if ($validated['access_role'] === 'super_admin') {
            $user->update([
                'isAdmin' => 1,
                'is_support' => 0,
            ]);

            return redirect()->back()->with('success', 'دسترسی کاربر به مدیر کل تغییر یافت.');
        }

        if ($validated['access_role'] === 'support_admin') {
            $user->update([
                'isAdmin' => 0,
                'is_support' => 1,
            ]);

            return redirect()->back()->with('success', 'دسترسی کاربر به ادمین پشتیبانی تغییر یافت.');
        }

        $user->update([
            'isAdmin' => 0,
            'is_support' => 0,
        ]);

        return redirect()->back()->with('success', 'دسترسی کاربر به کاربر معمولی تغییر یافت.');
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

        if($request->has('user')) {
            $search = $request->input('user');
            $user = $request->user;
           
            $Users = User::where('username', $user)
                ->orWhere('mobile', $user)
                ->orWhere('name', 'like', '%' . $user . '%')
                ->orWhere('last_name', 'like', '%' . $user . '%')
                ->orWhere('nam', 'like', '%' . $user . '%')
                ->get();

           
        }else {
            $Users = null;
            $search = null;
            $Bot = null;
        }    

        return view('dashboard.userSearch',compact('Users','search'));
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
