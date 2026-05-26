<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot1;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;



class Bot1Controller extends Controller
{
    public function dashboard() {

        $AllUsers = Bot1::count();
        $ActiveUsers = Bot1::where('vip', '>',0)->where('status',1)->count();
        $now = Carbon::now();
        $deactiveUsers = Bot1::where('step', 3)
        ->where('status', 0)
        ->where('vip', '>', 0)
        ->whereNotNull('exp_vip')
        ->where('exp_vip', '<', Carbon::now())
        ->count();

        $Bot = "Bot1";
        return view("dashboard.Botdashboard", compact("AllUsers", "ActiveUsers", "deactiveUsers", "Bot"));

    }

    public function users() {

        $Users = Bot1::paginate(25000);
        session()->put('backlink', route('bot1.users'));
        $Bot = "Bot1";
        return view("dashboard.BotUsers", compact("Users","Bot"));

    }

    public function activeUsers() {

        $Users = Bot1::where('vip', '>',0)->where('status',1)->get();
        session()->put('backlink', route('bot1.activeUsers'));
        $Bot = "Bot1";
        return view("dashboard.BotUsers", compact("Users","Bot"));

    }

    public function deactiveUsers() {

        $now = Carbon::now();
        $Users = Bot1::where('step', 3)
        ->where('status', 0)
        ->where('vip', '>', 0)
        ->whereNotNull('exp_vip')
        ->where('exp_vip', '<', Carbon::now())
        ->get();
        session()->put('backlink', route('bot1.deactiveUsers'));
        $Bot = "Bot1";
        return view("dashboard.BotUsers", compact("Users","Bot"));

    }

    public function botUserInfo(Bot1 $user) {

        $SiteUser = User::where('mobile',$user->mobile)->first();
        return view("dashboard.BotUserInfo", compact("user", "SiteUser"));

    }

    public function UpdateUserInfo(Request $request, Bot1 $user) {
        
        dd($request->all());
        $vipDays = (int) $request->input('vip', 0);
        $additionalDays = (int) $request->input('addday', 0);

        // تعیین تعداد روزهای جدید VIP: اولویت با $vipDays است، در غیر این صورت $additionalDays استفاده می‌شود.
        $daysToAdd = 0;
        if ($vipDays > 0) {
            $daysToAdd = $vipDays;
        } elseif ($additionalDays > 0) {
            $daysToAdd = $additionalDays;
        }

        // ---------------------------------------------
        // اگر daysToAdd صفر باشد، یعنی قصد لغو اشتراک را داریم یا داده‌ای وارد نشده.
        if ($daysToAdd === 0) {
            // منطق لغو اشتراک
            $user->vip = null; // یا null، بستگی به طراحی دیتابیس داره. 0 معمولاً بهتره
            $user->start_vip = "0000-00-00 00:00:00";
            $user->exp_vip = null;
            $user->status = 0;

        } else {
            // منطق تمدید/فعال‌سازی اشتراک

            // 1. تعیین زمان شروع اشتراک جدید
            // اگر کاربر همین الآن VIP فعال دارد و تاریخ انقضا (exp_vip) در آینده است،
            // شروع اشتراک جدید باید از تاریخ انقضای فعلی باشد (تمدید).
            // در غیر این صورت، از زمان کنونی شروع می‌شود (فعال‌سازی).
            
            $currentExpiration = $user->exp_vip ? Carbon::parse($user->exp_vip) : null;
            $now = Carbon::now();
            
            // زمان شروع اشتراک جدید: یا الآن، یا تاریخ انقضای قبلی (اگر هنوز معتبر است)
            if ($currentExpiration && $currentExpiration->greaterThan($now)) {
                $startOfNewPeriod = $currentExpiration; // تمدید از پایان دوره قبلی
            } else {
                $startOfNewPeriod = $now; // شروع از الآن
            }

            // 2. محاسبه تاریخ انقضای جدید
            $newExpiration = $startOfNewPeriod->copy()->addDays($daysToAdd);

            // 3. به‌روزرسانی فیلدهای کاربر
            $user->vip = $daysToAdd; // ذخیره تعداد روزهای اشتراک فعلی (اختیاری)
            
            // اگر start_vip نال بود، آن را تنظیم کن
            if (is_null($user->start_vip) || ($currentExpiration && $currentExpiration->lessThanOrEqualTo($now))) {
                // اگر تا حالا VIP نداشته یا VIP قبلیش تموم شده
                $user->start_vip = $now;
            }
            
            $user->exp_vip = $newExpiration;
            $user->status = 1; // فعال‌سازی وضعیت
        }

        // ذخیره تغییرات
        $user->save();

        @file_get_contents("https://roohi.trade/roohi_ag_bp/userSendAcceptNotifs.php?user_id=$user->user_id&mobile=$user->mobile&day=$daysToAdd");

        return redirect()->route('bot1.botUserInfo', $user->id)->with('success', 'اطلاعات کاربر با موفقیت به‌روزرسانی شد.');

    }

}
