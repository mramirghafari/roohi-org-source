<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function usersExcel(Request $request)
    {
        $scope = (string) $request->query('scope', 'all');

        $usersQuery = User::query()->with(['subscribes', 'latestBalanceLog']);

        if ($scope === 'active') {
            $usersQuery->withActiveVip();
        } elseif ($scope === 'deactive') {
            $usersQuery->doesntHave('subscribes');
        } elseif ($scope === 'left') {
            $usersQuery->withExpiredVip();
        }

        $users = $usersQuery->get();

        $headers = ['ردیف', 'نام کاربر', 'موبایل', 'شروع اشتراک', 'پایان اشتراک', 'مانده اشتراک', 'LBank UID', 'CoincAll UID', 'موجودی ال بانک'];

        $fileName = 'users-export-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($users, $headers) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, $headers);

            foreach ($users as $index => $user) {
            $activeSubscribe = $user->subscribes
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

            $remainingDays = '-';
            if ($activeSubscribe && $activeSubscribe->exp_vip) {
                $remainingDays = max(0, (int) ceil(now()->diffInSeconds($activeSubscribe->exp_vip, false) / 86400)) . ' روز';
            }

                fputcsv($output, [
                    $index + 1,
                    $user->nam,
                    $user->mobile,
                    $activeSubscribe ? $activeSubscribe->start_vip->format('Y-m-d') : '-',
                    $activeSubscribe ? $activeSubscribe->exp_vip->format('Y-m-d') : '-',
                    $remainingDays,
                    $user->lbank_uid ?? '-',
                    $user->coincall_uid ?? '-',
                    $user->latestBalanceLog ? '$' . intval($user->latestBalanceLog->balance) : 'سینک نشده',
                ]);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
