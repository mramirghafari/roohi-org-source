<?php

namespace App\Http\Controllers;

use App\Models\UserWallet;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request, WalletService $walletService)
    {
        $user = $request->user();
        $wallet = $walletService->ensureWallet($user);

        $transactions = WalletTransaction::query()
            ->where('user_id', $user->id)
            ->latest('id')
            ->limit(50)
            ->get();

       /* return response()->json([
            'wallet' => [
                'toman_balance' => $wallet->toman_balance,
                'usdt_balance' => $wallet->usdt_balance,
                'stars_balance' => $wallet->stars_balance,
            ],
            'transactions' => $transactions,
        ]); */
        
        return view('dashboard.wallet', [
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }

    public function deposit(Request $request, WalletService $walletService)
    {
        $validated = $request->validate([
            'asset' => 'required|in:' . implode(',', UserWallet::ASSETS),
            'amount' => 'required|numeric|gt:0',
            'description' => 'nullable|string|max:500',
        ]);

        $walletService->deposit(
            $request->user(),
            $validated['asset'],
            (float) $validated['amount'],
            $validated['description'] ?? 'واریز به کیف پول',
            $request->user(),
        );

        return back()->with('success', 'واریز با موفقیت ثبت شد.');
    }

    public function withdraw(Request $request, WalletService $walletService)
    {
        $validated = $request->validate([
            'asset' => 'required|in:' . implode(',', UserWallet::ASSETS),
            'amount' => 'required|numeric|gt:0',
            'description' => 'nullable|string|max:500',
        ]);

        $walletService->withdraw(
            $request->user(),
            $validated['asset'],
            (float) $validated['amount'],
            $validated['description'] ?? 'برداشت از کیف پول',
            $request->user(),
        );

        return back()->with('success', 'برداشت با موفقیت ثبت شد.');
    }

    public function transferByMobile(Request $request, WalletService $walletService)
    {
        $validated = $request->validate([
            'receiver_mobile' => 'required|string|max:30',
            'asset' => 'required|in:' . implode(',', UserWallet::ASSETS),
            'amount' => 'required|numeric|gt:0',
            'description' => 'nullable|string|max:500',
        ]);

        $walletService->transferByMobile(
            $request->user(),
            trim($validated['receiver_mobile']),
            $validated['asset'],
            (float) $validated['amount'],
            $validated['description'] ?? 'انتقال کیف پول',
        );

        return back()->with('success', 'انتقال با موفقیت انجام شد.');
    }

    public function swap(Request $request, WalletService $walletService)
    {
        $validated = $request->validate([
            'stars_amount' => 'required|integer|min:1',
            'convert_to' => 'required|in:toman,usdt',
        ]);

        $result = $walletService->swapStars(
            $request->user(),
            (int) $validated['stars_amount'],
            $validated['convert_to'],
        );

        $assetLabel = $result['asset'] === UserWallet::ASSET_TOMAN ? 'تومان' : 'تتر';

        return back()->with('success', "تبدیل با موفقیت انجام شد: {$result['stars_spent']} STARS به {$result['asset_amount']} {$assetLabel}");
    }

    public function operation(Request $request, WalletService $walletService)
    {
        $validated = $request->validate([
            'operation_type' => 'required|in:deposit,withdraw,transfer',
            'asset' => 'required|in:toman,usdt',
            'amount' => 'required|numeric|gt:0',
            'sheba' => 'nullable|required_if:operation_type,withdraw|string|max:34',
            'receiver_mobile' => 'nullable|required_if:operation_type,transfer|string|max:30',
        ]);

        if ($validated['operation_type'] === 'deposit') {
            $walletService->deposit(
                $request->user(),
                $validated['asset'],
                (float) $validated['amount'],
                'درخواست واریز کاربر',
                $request->user(),
            );

            return back()->with('success', 'واریز با موفقیت ثبت شد.');
        }

        if ($validated['operation_type'] === 'withdraw') {
            $walletService->withdraw(
                $request->user(),
                $validated['asset'],
                (float) $validated['amount'],
                'درخواست برداشت - شبا: ' . ($validated['sheba'] ?? '-'),
                $request->user(),
            );

            return back()->with('success', 'برداشت با موفقیت ثبت شد.');
        }

        $walletService->transferByMobile(
            $request->user(),
            trim((string) $validated['receiver_mobile']),
            $validated['asset'],
            (float) $validated['amount'],
            'انتقال کیف پول به کاربر دیگر',
        );

        return back()->with('success', 'انتقال کیف پول با موفقیت انجام شد.');
    }
}
