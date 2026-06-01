<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionTransaction;
use App\Services\SubscriptionActivationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SubscriptionPaymentReviewController extends Controller
{
    public function index(): View
    {
        $transactions = SubscriptionTransaction::query()
            ->with(['user:id,nam,mobile', 'subscriptionPlan'])
            ->where('payment_method', 'card_to_card')
            ->latest('id')
            ->get();

        return view('dashboard.subscription-payments.index', compact('transactions'));
    }

    public function approve(SubscriptionTransaction $transaction): RedirectResponse
    {
        if ($transaction->payment_method !== 'card_to_card') {
            return redirect()->route('subscription-payments.index')->with('error', 'این تراکنش کارت به کارت نیست.');
        }

        $shouldSendSms = false;
        $smsUserId = 0;
        $smsMonths = 0;

        DB::transaction(function () use ($transaction, &$shouldSendSms, &$smsUserId, &$smsMonths) {
            $fresh = SubscriptionTransaction::query()
                ->where('id', $transaction->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($fresh->status !== SubscriptionTransaction::STATUS_PENDING) {
                return;
            }

            $refId = 'CARD-' . $fresh->id;
            $subscribeId = app(SubscriptionActivationService::class)->activate(
                (int) $fresh->user_id,
                (int) $fresh->plan_months,
                (int) $fresh->amount,
                $refId
            );

            $fresh->update([
                'admin_user_id' => auth()->id(),
                'subscribe_id' => $subscribeId,
                'status' => SubscriptionTransaction::STATUS_SUCCESS,
                'ref_id' => $refId,
                'activated_at' => now(),
                'admin_reviewed_at' => now(),
                'paid_at' => $fresh->manual_paid_at ?: now(),
                'message' => 'پرداخت کارت به کارت توسط مدیریت تایید شد.',
            ]);

            $shouldSendSms = true;
            $smsUserId = (int) $fresh->user_id;
            $smsMonths = (int) $fresh->plan_months;
        });

        if ($shouldSendSms && $smsUserId > 0 && $smsMonths > 0) {
            app(SubscriptionActivationService::class)->sendActivationSms($smsUserId, $smsMonths);
        }

        return redirect()->route('subscription-payments.index')->with('success', 'اشتراک کاربر فعال شد.');
    }

    public function reject(SubscriptionTransaction $transaction): RedirectResponse
    {
        if ($transaction->payment_method !== 'card_to_card') {
            return redirect()->route('subscription-payments.index')->with('error', 'این تراکنش کارت به کارت نیست.');
        }

        if ($transaction->status === SubscriptionTransaction::STATUS_PENDING) {
            $transaction->update([
                'admin_user_id' => auth()->id(),
                'status' => SubscriptionTransaction::STATUS_CANCEL,
                'admin_reviewed_at' => now(),
                'message' => 'درخواست کارت به کارت توسط مدیریت رد شد.',
            ]);
        }

        return redirect()->route('subscription-payments.index')->with('success', 'درخواست کارت به کارت رد شد.');
    }

    public function receipt(SubscriptionTransaction $transaction)
    {
        abort_unless($transaction->receipt_path, 404);
        abort_unless(Storage::disk('local')->exists($transaction->receipt_path), 404);

        return Storage::disk('local')->download($transaction->receipt_path);
    }
}