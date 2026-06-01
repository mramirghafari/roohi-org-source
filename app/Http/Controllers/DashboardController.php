<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Subscribe; // اسم مدل رو مطابق پروژه خودت بذار
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bot1;
use App\Models\Bot2;
use App\Models\Signal;
use App\Models\AppSetting;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionTransaction;
use App\Http\Controllers\Admin\SalesTeamDashboardController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    private const REFERRAL_JOIN_CONDITION_KEY = 'referral_join_condition';

    public function dashboard () {

        if ($this->shouldShowSalesTeamDashboard()) {
            return app(SalesTeamDashboardController::class)->index();
        }

        $startOfWeek = now()->startOfWeek(Carbon::SATURDAY); // شروع هفته: شنبه
        $now         = now();

        
        
        $thisWeekVisibleSignals = Signal::where('isVisible', 1)
            ->whereBetween('created_at', [$startOfWeek, $now])
            ->count();

        $thisWeekVisibleSignalsWait = Signal::where('isVisible', 1)
        ->where('result',1)
        ->whereBetween('created_at', [$startOfWeek, $now])
        ->count();

        $thisWeekVisibleSignalsSood = Signal::where('isVisible', 1)
        ->where('result',2)
        ->whereBetween('created_at', [$startOfWeek, $now])
        ->count();

        $thisWeekVisibleSignalsZarar = Signal::where('isVisible', 1)
        ->where('result',3)
        ->whereBetween('created_at', [$startOfWeek, $now])
        ->count();


        $weeklySignals = $this->getWeeklyVisibleSignals(); // همون متدی که دادم

        $chartCategories = array_column($weeklySignals, 'day_fa');
        $chartData       = array_map(fn($x) => (int) $x['count'], $weeklySignals);



        $user = auth()->user();

        // لینک دعوت
        $inviteLink = route('ref.link', ['code' => $user->ref_code]);
        // تعداد زیرمجموعه مستقیم (Level 1)
        $joinCondition = AppSetting::getValue(self::REFERRAL_JOIN_CONDITION_KEY, 'vip_active');
        $isVipCondition = $joinCondition === 'vip_active';

        if ($isVipCondition) {
            $directReferralsCount = $user->referrals()
                ->whereHas('subscribes', function ($q) {
                    $q->where('status', 1)
                        ->whereNotNull('exp_vip')
                        ->where('exp_vip', '>', now());
                })
                ->count();

            $activeDirectReferralsCount = $directReferralsCount;
            $totalNetworkCount = $user->activeDescendantsCountAllBySubscribe();
            $activeNetworkCount = $totalNetworkCount;
        } else {
            $directReferralsCount = $user->referrals()->count();
            $activeDirectReferralsCount = $user->referrals()
                ->whereHas('subscribes', function ($q) {
                    $q->where('status', 1)
                        ->whereNotNull('exp_vip')
                        ->where('exp_vip', '>', now());
                })
                ->count();

            $totalNetworkCount = $user->descendantsCountAll();
            $activeNetworkCount = $user->activeDescendantsCountAllBySubscribe();
        }


        return view('dashboard.dashboard', compact('thisWeekVisibleSignals','thisWeekVisibleSignalsWait','thisWeekVisibleSignalsSood','thisWeekVisibleSignalsZarar','chartCategories', 'chartData', 'inviteLink', 'directReferralsCount', 'activeDirectReferralsCount', 'totalNetworkCount', 'activeNetworkCount'));

    }

    private function shouldShowSalesTeamDashboard(): bool
    {
        $user = auth()->user();

        if (!$user || (int) $user->isAdmin === 1) {
            return false;
        }

        return $user->supportGroups()->exists()
            || $user->hasRole('marketer')
            || $user->hasRole('sales-expert')
            || $user->hasRole('sales-manager');
    }

    public function channel()
    {
        $user = auth()->user();
        // آخرین/فعال‌ترین اشتراک کاربر (اگر ساختارت فرق داره، همین خط رو عوض کن)
        $subscribe = Subscribe::where('user_id', $user->id)->where('status',1)->first();

        $signals = collect();
        $lastSignalAt = Signal::where('isVisible', 1)
            ->orderByDesc('created_at')
            ->value('created_at');

        if ((int) $user->isAdmin === 1) {
            $signals = Signal::query()
                ->where('isVisible', 1)
                ->get();

            return view('dashboard.channel', [
                'subscribe'    => $subscribe,
                'signals'      => $signals,
                'lastSignalAt' => $lastSignalAt,
            ]);
        }

        if ($subscribe && $subscribe->start_vip && $subscribe->exp_vip) {
            $start = $subscribe->start_vip;
            $end   = $subscribe->exp_vip;
            

            // 1) لیست سیگنال‌ها در بازه عضویت - جدیدترین اول
            $signals = Signal::query()
                ->where('isVisible', 1)
                ->whereBetween('created_at', [$start, $end])
                ->get();

        }


        $user = auth()->user()->load('latestBalanceLog');
        $latest = $user->latestBalanceLog?->balance;
        

        if (isset($latest) && $latest < 50  ) {
          //  return redirect()->route('dashboard')->with('deposit_error', 'برای استفاده از کانال سیگنال باید حساب شما در ال بانک بیشتر از 50 دلار موجودی داشته باشد. لطفا برای فعال شدن کانال حساب خود را شارژ کنید.');
        }



        return view('dashboard.channel', [
            'subscribe'    => $subscribe,
            'signals'      => $signals,
            'lastSignalAt' => $lastSignalAt,
        ]);
    }

    public function newSignal() {

        return view('dashboard.newSignal');

    }

    public function subscription()
    {
        $user = auth()->user();

        // رکورد دمو (type=4) کاربر جاری برای استفاده در ویو
        $hasDemoSub = Subscribe::query()
            ->where('user_id', $user->id)
            ->where('type', 4)
            ->exists();

        $demoSub = Subscribe::query()
            ->where('user_id', $user->id)
            ->where('type', 4)
            ->orderByDesc('exp_vip')
            ->first();
        $subscriptionTransactions = collect();
        if (Schema::hasTable('subscription_transactions')) {
            $subscriptionTransactions = SubscriptionTransaction::query()
                ->with('subscriptionPlan')
                ->where('user_id', $user->id)
                ->latest('id')
                ->limit(50)
                ->get();
        }

        $subscriptionPlans = collect();
        if (Schema::hasTable('subscription_plans')) {
            $subscriptionPlans = SubscriptionPlan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        }

        $legacyRows = Subscribe::query()
            ->where('user_id', $user->id)
            ->when(
                Schema::hasTable('subscription_transactions'),
                fn ($query) => $query->whereNotIn('id', SubscriptionTransaction::query()
                    ->where('user_id', $user->id)
                    ->whereNotNull('subscribe_id')
                    ->pluck('subscribe_id'))
            )
            ->get()
            ->map(function ($item) {
                $planMonths = 0;
                if ((int) $item->vip >= 1 && (int) $item->vip <= 3) {
                    $planMonths = (int) $item->vip;
                }

                return (object) [
                    'plan_months' => $planMonths,
                    'status' => 'success',
                    'amount' => (int) ($item->price ?? 0),
                    'ref_id' => $item->tracking_code ?? null,
                    'message' => 'سابقه قدیمی اشتراک',
                    'created_at' => $item->created_at ?? $item->register_date ?? $item->start_vip,
                ];
            });

        $subscriptionTransactions = $subscriptionTransactions
            ->concat($legacyRows)
            ->sortByDesc(function ($item) {
                return $item->created_at;
            })
            ->values();

        // فقط اشتراک‌های فعال (status=1)
        $sub = Subscribe::query()
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->orderByDesc('exp_vip')   // یا created_at / id
            ->first();

        $StartDate = $sub?->start_vip ? Carbon::parse($sub->start_vip) : null;
        $EndDate   = $sub?->exp_vip   ? Carbon::parse($sub->exp_vip)   : null;

        $days = ($StartDate && $EndDate)
            ? $StartDate->diffInDays($EndDate)
            : 0;

        $planType = match (true) {
            $days >= 85 && $days <= 95   => 3,
            $days >= 55 && $days <= 65   => 2,
            $days >= 25 && $days <= 35   => 1,
            default                      => 0,
        };

        $remainingDays = ($EndDate)
            ? (int) now()->diffInDays($EndDate, false)
            : 0;

        $totalDays = ($StartDate && $EndDate)
            ? $StartDate->diffInDays($EndDate)
            : 0;

        $remainingPercent = ($totalDays > 0)
            ? round(($remainingDays / $totalDays) * 100)
            : 0;

        // وضعیت برای ویو
        // اگر رکورد status=1 پیدا نشد یعنی هیچ اشتراک فعالی نداره
        $status = $sub ? 'active' : 'none';

        // اگر status=1 هست ولی تاریخش گذشته (دیتا خراب/قدیمی)، اینو expired کن
        if ($sub && $EndDate && $EndDate->isPast()) {
            $status = 'expired';
        }

        return view('dashboard.subscription', compact(
            'sub',
            'StartDate',
            'EndDate',
            'remainingDays',
            'totalDays',
            'remainingPercent',
            'status',
            'days',
            'planType',
            'subscriptionPlans',
            'subscriptionTransactions',
            'hasDemoSub',
            'demoSub'
        ));
    }

    public function autoTradeSetting() {
        return view('dashboard.auto-trade');
    }


    public function profile() {
        return view('dashboard.profile');
    }

    public function update_profile(Request $request) {

        $user = auth()->user();

        $request->validate([
            'nam' => 'required|string|max:255',
            'gender' => 'required|digits:1',
            'birthdate' => 'required|string|max:255',
        ]);

        $user->update($request->only(['nam', 'gender','birthdate']));

        return redirect()->route('dashboard.profile')->with('success', 'اطلاعات پروفایل شما با موفقیت به‌روزرسانی شد.');

    }

    public function profile_uid() {
        return view('dashboard.profile_uid');
    }
    public function update_uid(Request $request) {

    //dd($request->all());
        $request->validate([
            'lbank_uid' => 'max:40',
            'coincall_uid' => 'max:40',
        ]);

        $lbankuid = $request->input('lbank_uid');
        $coincalluid = $request->input('coincall_uid');

        $user = auth()->user();
        $user->coincall_uid = $coincalluid;
        $user->save();
    

        if($lbankuid != null) {

        
        // ✅ ساخت URL
        $url = 'https://roohi.trade/newbot/test_user_info.php';

        // ✅ درخواست با HTTP Client لاراول (cURL-based)
        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->get($url, [
                    'mch' => $lbankuid,
                ]);
        } catch (\Throwable $e) {
            return back()->withErrors([
                'error' => 'خطا در اتصال به سرور ال بانک',
            ]);
        }

        // ✅ بررسی HTTP Status
        if (!$response->ok()) {
            return back()->withErrors([
                'error' => 'پاسخ نامعتبر از ال بانک',
            ]);
        }

        $json = $response->json();

        //dd($json);

        // ✅ اعتبارسنجی ساختار JSON
        if (
            !isset($json['success']) ||
            !isset($json['inTeam']) ||
            !isset($json['data'])
        ) {
            return back()->withErrors([
                'error' => 'ساختار پاسخ ال بانک نامعتبر است',
            ]);
        }

        // ✅ استخراج فیلدها
        $success    = (bool) $json['success'];
        $inTeam    = (bool) $json['inTeam'];
        if($success && $inTeam){

           
            $user->lbank_uid = $request->input('lbank_uid');
            $user->save();

                       
        } else {
        return back()->withErrors([
            'error' => 'شناسه کاربری وارد شده معتبر نیست یا در تیم ما عضو نیست.',
        ]);

        }

        

        }
        return redirect()->route('dashboard.profile.uid')->with('success', 'UID شما با موفقیت ثبت شد.');
        
    }

    public function profile_api_set() {
        return view('dashboard.profile_api_set');
    }

    public function notifications() {

        $User = auth()->user();
        $notifications = $User->notifs()->orderBy('created_at', 'desc')->get();

        return view('dashboard.notifications', compact('notifications'));
    }

    public function readAllNotifs() {

        $user = auth()->user();
        
        // تمام نوتیفیکیشن‌های کاربر رو به‌عنوان خوانده‌شده علامت‌گذاری کن
        $updated = $user->notifs()
            ->where('status', 0)
            ->update(['status' => 1]);

        return redirect()->route('notifications')->with('success','همه ی اعلانات به حالت خوانده شده تبدیل شد.');
    }

    public function notifRead(Request $request) {
        $notifId = $request->input('notif_id');
        $notif = auth()->user()->notifs()->where('id', $notifId)->first();

        if ($notif) {
            $notif->status = 1; // علامت‌گذاری به‌عنوان خوانده‌شده
            $notif->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
    
    public function notifDelete(Request $request) {
        $notifId = $request->input('notif_id');
        $notif = auth()->user()->notifs()->where('id', $notifId)->first();

        if ($notif) {
            $notif->delete(); // حذف نوتیفیکیشن

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function userSearch(Request $request) {

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


    
    public function lbank_checkBalance() {


        return view("dashboard.lbank_checkBalance");

    }


    public function referralsPage() {
        $user = auth()->user();

        $inviteLink = route('ref.link', ['code' => $user->ref_code]);
        $joinCondition = AppSetting::getValue(self::REFERRAL_JOIN_CONDITION_KEY, 'vip_active');
        $isVipCondition = $joinCondition === 'vip_active';

        if ($isVipCondition) {
            $directReferralsCount = $user->referrals()
                ->whereHas('subscribes', function ($q) {
                    $q->where('status', 1)
                        ->whereNotNull('exp_vip')
                        ->where('exp_vip', '>', now());
                })
                ->count();

            $activeDirectReferralsCount = $directReferralsCount;
            $totalNetworkCount = $user->activeDescendantsCountAllBySubscribe();
            $activeNetworkCount = $totalNetworkCount;
        } else {
            $directReferralsCount = $user->referrals()->count();
            $activeDirectReferralsCount = $user->referrals()
                ->whereHas('subscribes', function ($q) {
                    $q->where('status', 1)
                        ->whereNotNull('exp_vip')
                        ->where('exp_vip', '>', now());
                })
                ->count();

            $totalNetworkCount = $user->descendantsCountAll();
            $activeNetworkCount = $user->activeDescendantsCountAllBySubscribe();
        }

        return view('dashboard.referrals', compact(
            'inviteLink',
            'directReferralsCount',
            'activeDirectReferralsCount',
            'totalNetworkCount',
            'activeNetworkCount'
        ));
    }




    public function getWeeklyVisibleSignals(): array
    {
        // مپ روزهای هفته فارسی
        $faDays = [
            Carbon::SATURDAY  => 'شنبه',
            Carbon::SUNDAY    => 'یکشنبه',
            Carbon::MONDAY    => 'دوشنبه',
            Carbon::TUESDAY   => 'سه‌شنبه',
            Carbon::WEDNESDAY => 'چهارشنبه',
            Carbon::THURSDAY  => 'پنج‌شنبه',
            Carbon::FRIDAY    => 'جمعه',
        ];

        $startOfWeek = now()->startOfWeek(Carbon::SATURDAY)->startOfDay();
        $endOfWeek   = $startOfWeek->copy()->addDays(6)->endOfDay();

        // گرفتن تعداد واقعی از دیتابیس
        $rows = Signal::selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('isVisible', 1)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy('day')
            ->pluck('total', 'day'); // key => Y-m-d

        $result = [];

        foreach (CarbonPeriod::create($startOfWeek, '1 day', $startOfWeek->copy()->addDays(6)) as $date) {
            $key = $date->format('Y-m-d');

            $result[] = [
                'date'       => $key,
                'day_fa'     => $faDays[$date->dayOfWeek],
                'day_index'  => $date->dayOfWeek, // اگر بعداً خواستی سورت/چارت
                'count'      => (int) ($rows[$key] ?? 0),
                'is_future'  => $date->isFuture(), // اختیاری (برای UI)
            ];
        }

        return $result;
    }
}
