<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Bot2;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

class Bot2Controller extends Controller
{
    public function dashboard() {

        $AllUsers = Bot2::count();
        $ActiveUsers = Bot2::where('status', 1)->whereNotNull('lbank_uid')->where('step',12)->count();
        $now = Carbon::now();
    
        $deactiveUsers = Bot2::where('status', 0)->where('lbank_uid',null)->where('step',12)->count();
        $Bot = "Bot2";
        return view("dashboard.Botdashboard", compact("AllUsers", "ActiveUsers", "deactiveUsers","Bot"));

    }

    public function users() {

        session()->put('backlink', route('bot2.users'));
        return $this->usersListView('all');

    }

    public function activeUsers() {

        session()->put('backlink', route('bot2.activeUsers'));
        return $this->usersListView('active');

    }

    public function deactiveUsers() {

        session()->put('backlink', route('bot2.deactiveUsers'));
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
            ->with('latestBalance')
            ->latest('id')
            ->skip($start)
            ->take($length)
            ->get();

        $rows = $users->map(function (Bot2 $user, int $index) use ($start) {
            return [
                '<bdi>' . number_format($start + $index + 1) . '</bdi>',
                '<bdi>' . e((string) $user->nam) . '</bdi>',
                '<bdi>' . e((string) $user->mobile) . '</bdi>',
                '<small>' . e(trim(substr((string) $user->name, 0, 30) . ' ' . substr((string) $user->last_name, 0, 30))) . '</small>',
                $user->username ? '<small><a href="https://t.me/' . e((string) $user->username) . '" target="_blank">' . e((string) $user->username) . '</a></small>' : '<small>-</small>',
                '<span class="badge bg-label-primary me-1">' . e((string) (optional($user->latestBalance)->balance ?? 'سینک نشده')) . '</span>',
                $user->lbank_uid !== null && (int) $user->status === 1 ? '<span class="badge bg-label-success me-1">فعال</span>' : '<span class="badge bg-label-danger me-1">غیرفعال</span>',
                '<a href="' . route('bot2.botUserInfo', $user->id) . '" class="btn btn-sm btn-info">مشاهده</a>',
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
        $fileName = 'bot2-users-' . $scope . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($scope) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['ردیف', 'نام کاربر', 'شماره موبایل', 'نام تلگرام', 'یوزرنیم تلگرام', 'موجودی ال بانک', 'وضعیت']);

            $row = 1;
            $this->usersScopedQuery($scope)->with('latestBalance')->chunkById(500, function ($users) use ($handle, &$row) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $row++,
                        $user->nam,
                        $user->mobile,
                        trim($user->name . ' ' . $user->last_name),
                        $user->username,
                        optional($user->latestBalance)->balance ?? 'سینک نشده',
                        $user->lbank_uid !== null && (int) $user->status === 1 ? 'فعال' : 'غیرفعال',
                    ]);
                }
            });

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function botUserInfo(Bot2 $user) {

        $Bot = "Bot2";
        $SiteUser = User::where('mobile',$user->mobile)->first();
        return view("dashboard.BotUserInfo", compact("user", "Bot","SiteUser"));

    }

    private function usersListView(string $scope)
    {
        $Bot = "Bot2";
        $botUsersAjaxUrl = route('bot2.users.data', ['scope' => $scope]);
        $botUsersExportUrl = route('bot2.users.export', ['scope' => $scope]);

        return view("dashboard.BotUsers", compact("Bot", "botUsersAjaxUrl", "botUsersExportUrl"));
    }

    private function usersScopedQuery(string $scope): Builder
    {
        $query = Bot2::query();

        if ($scope === 'active') {
            return $query->where('status', 1)->whereNotNull('lbank_uid')->where('step', 12);
        }

        if ($scope === 'deactive') {
            return $query->where('step', 11)
                ->where('status', 0)
                ->where('vip', null)
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
                ->orWhere('user_id', 'like', '%' . $search . '%')
                ->orWhere('lbank_uid', 'like', '%' . $search . '%');
        });
    }




}
