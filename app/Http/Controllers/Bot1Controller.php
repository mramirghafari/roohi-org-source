<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bot1;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Builder;



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

        session()->put('backlink', route('bot1.users'));
        return $this->usersListView('all');

    }

    public function activeUsers() {

        session()->put('backlink', route('bot1.activeUsers'));
        return $this->usersListView('active');

    }

    public function deactiveUsers() {

        session()->put('backlink', route('bot1.deactiveUsers'));
        return $this->usersListView('deactive');

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

        $users = $filteredQuery
            ->latest('id')
            ->skip($start)
            ->take($length)
            ->get();

        $rows = $users->map(function (Bot1 $user, int $index) use ($start) {
            return [
                '<bdi>' . number_format($start + $index + 1) . '</bdi>',
                '<bdi>' . e((string) $user->nam) . '</bdi>',
                '<bdi>' . e((string) $user->mobile) . '</bdi>',
                '<small>' . e(trim(substr((string) $user->name, 0, 30) . ' ' . substr((string) $user->last_name, 0, 30))) . '</small>',
                $user->username ? '<small><a href="https://t.me/' . e((string) $user->username) . '" target="_blank">' . e((string) $user->username) . '</a></small>' : '<small>-</small>',
                (int) $user->status === 1 ? '<span class="badge bg-label-success me-1">فعال</span>' : '<span class="badge bg-label-danger me-1">غیرفعال</span>',
                '<a href="' . route('bot1.botUserInfo', $user->id) . '" class="btn btn-sm btn-info">مشاهده</a>',
            ];
        })->values();

        return response()->json([
            'draw' => (int) $request->input('draw', 0),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $rows,
        ]);
    }

    public function export(Request $request)
    {
        $scope = (string) $request->query('scope', 'all');
        $fileName = 'bot1-users-' . $scope . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($scope) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['ردیف', 'نام کاربر', 'شماره موبایل', 'نام تلگرام', 'یوزرنیم تلگرام', 'وضعیت']);

            $row = 1;
            $this->usersScopedQuery($scope)->chunkById(500, function ($users) use ($handle, &$row) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $row++,
                        $user->nam,
                        $user->mobile,
                        trim($user->name . ' ' . $user->last_name),
                        $user->username,
                        (int) $user->status === 1 ? 'فعال' : 'غیرفعال',
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function botUserInfo(Bot1 $user) {

        $SiteUser = User::where('mobile',$user->mobile)->first();
        return view("dashboard.BotUserInfo", compact("user", "SiteUser"));

    }

    private function usersListView(string $scope)
    {
        $Bot = "Bot1";
        $botUsersAjaxUrl = route('bot1.users.data', ['scope' => $scope]);
        $botUsersExportUrl = route('bot1.users.export', ['scope' => $scope]);

        return view("dashboard.BotUsers", compact("Bot", "botUsersAjaxUrl", "botUsersExportUrl"));
    }

    private function usersScopedQuery(string $scope): Builder
    {
        $query = Bot1::query();

        if ($scope === 'active') {
            return $query->where('vip', '>', 0)->where('status', 1);
        }

        if ($scope === 'deactive') {
            return $query->where('step', 3)
                ->where('status', 0)
                ->where('vip', '>', 0)
                ->whereNotNull('exp_vip')
                ->where('exp_vip', '<', Carbon::now());
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
                ->orWhere('user_id', 'like', '%' . $search . '%');
        });
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
