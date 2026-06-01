<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\SubscriptionTransaction;
use App\Services\SubscriptionActivationService;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionPaymentController extends Controller
{
    private const CALLBACK_EXPIRE_MINUTES = 20;

    public function request(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'integer', 'exists:subscription_plans,id'],
            'payment_method' => ['required', 'in:gateway,card_to_card'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:5120'],
            'payer_card_number' => ['nullable', 'string', 'max:32'],
            'manual_paid_at' => ['nullable', 'string', 'max:32'],
        ]);

        $plan = SubscriptionPlan::query()
            ->where('is_active', true)
            ->findOrFail((int) $validated['plan']);

        if ($validated['payment_method'] === 'gateway') {
            return $this->requestGatewayPayment($request, $plan);
        }

        return $this->requestCardToCardPayment($request, $plan);
    }

    private function requestGatewayPayment(Request $request, SubscriptionPlan $plan): RedirectResponse
    {
        if (!$plan->gateway_enabled || !$plan->gateway_price) {
            return redirect()->route('subscription')->with([
                'payment_state' => 'error',
                'payment_message' => 'پرداخت درگاه برای این اشتراک فعال نیست.',
            ]);
        }

        $user = $request->user();

        $transaction = SubscriptionTransaction::query()->create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'plan_months' => $plan->duration_months,
            'amount' => $plan->gateway_price,
            'currency' => (string) config('zarinpal.currency', 'IRT'),
            'payment_method' => 'gateway',
            'status' => SubscriptionTransaction::STATUS_PENDING,
            'message' => 'در انتظار پرداخت کاربر',
            'expires_at' => now()->addMinutes(self::CALLBACK_EXPIRE_MINUTES),
        ]);

        $callbackUrl = route('subscription.payment.callback', [
            'tx' => $transaction->id,
        ]);

        try {
            $gateway = zarinpal()
                ->amount((int) $plan->gateway_price)
                ->request()
                ->description($plan->title . ' - کاربر #' . $user->id)
                ->callbackUrl($callbackUrl);

            if (!empty($user->mobile)) {
                $gateway->mobile((string) $user->mobile);
            }

            if (!empty($user->email)) {
                $gateway->email((string) $user->email);
            }

            $response = $gateway->send();
        } catch (\Throwable $exception) {
            Log::error('Subscription payment request failed', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);

            $transaction->update([
                'status' => SubscriptionTransaction::STATUS_ERROR,
                'message' => 'خطا در ارتباط با درگاه پرداخت',
            ]);

            return redirect()->route('subscription')->with([
                'payment_state' => 'error',
                'payment_message' => 'ارتباط با درگاه پرداخت برقرار نشد. لطفا دوباره تلاش کنید.',
            ]);
        }

        if (!$response->success()) {
            $errorCode = $response->error()->code();
            $errorMessage = $response->error()->message();

            $transaction->update([
                'status' => SubscriptionTransaction::STATUS_ERROR,
                'gateway_code' => $errorCode,
                'message' => $errorMessage,
            ]);

            return redirect()->route('subscription')->with([
                'payment_state' => 'error',
                'payment_message' => 'ارسال به درگاه ناموفق بود: ' . $errorMessage,
            ]);
        }

        $transaction->update([
            'gateway_code' => 100,
            'message' => 'ارسال به درگاه انجام شد',
        ]);

        return $response->redirect() ?? redirect()->route('subscription')->with([
            'payment_state' => 'error',
            'payment_message' => 'هدایت به درگاه انجام نشد. دوباره تلاش کنید.',
        ]);
    }

    private function requestCardToCardPayment(Request $request, SubscriptionPlan $plan): RedirectResponse
    {
        if (!$plan->card_to_card_enabled || !$plan->card_to_card_price) {
            return redirect()->route('subscription')->with([
                'payment_state' => 'error',
                'payment_message' => 'پرداخت کارت به کارت برای این اشتراک فعال نیست.',
            ]);
        }

        $rules = [];
        if ($plan->receipt_required) {
            $rules['receipt'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:5120'];
        }
        if ($plan->payer_card_required) {
            $rules['payer_card_number'] = ['required', 'string', 'max:32'];
        }
        if ($plan->paid_at_required) {
            $rules['manual_paid_at'] = ['required', 'string', 'max:32'];
        }

        $validated = $request->validate($rules, [
            'receipt.required' => 'تصویر رسید را بارگذاری کنید.',
            'payer_card_number.required' => 'شماره کارت واریزکننده را وارد کنید.',
            'manual_paid_at.required' => 'تاریخ و ساعت پرداخت را وارد کنید.',
        ]);

        $receiptPath = $request->hasFile('receipt')
            ? $request->file('receipt')->store('subscription-receipts', 'local')
            : null;
        $manualPaidAt = $this->parseManualPaidAt($validated['manual_paid_at'] ?? $request->input('manual_paid_at'));

        SubscriptionTransaction::query()->create([
            'user_id' => $request->user()->id,
            'subscription_plan_id' => $plan->id,
            'plan_months' => $plan->duration_months,
            'amount' => $plan->card_to_card_price,
            'currency' => (string) config('zarinpal.currency', 'IRT'),
            'payment_method' => 'card_to_card',
            'receipt_path' => $receiptPath,
            'payer_card_number' => $validated['payer_card_number'] ?? $request->input('payer_card_number'),
            'manual_paid_at' => $manualPaidAt,
            'status' => SubscriptionTransaction::STATUS_PENDING,
            'message' => 'در انتظار بررسی کارت به کارت توسط مدیریت',
        ]);

        return redirect()->route('subscription')->with([
            'payment_state' => 'success',
            'payment_message' => 'درخواست کارت به کارت ثبت شد و پس از تایید مدیریت، اشتراک فعال می‌شود.',
        ]);
    }

    public function callback(Request $request): RedirectResponse
    {
        $authority = (string) $request->query('Authority', '');
        $gatewayStatus = strtoupper((string) $request->query('Status', ''));
        $transactionId = (int) $request->query('tx');

        if ($transactionId <= 0) {
            return redirect()->route('subscription')->with([
                'payment_state' => 'error',
                'payment_message' => 'شناسه تراکنش نامعتبر است.',
            ]);
        }

        $transaction = SubscriptionTransaction::query()->find($transactionId);

        if (!$transaction) {
            return redirect()->route('subscription')->with([
                'payment_state' => 'error',
                'payment_message' => 'تراکنش مورد نظر پیدا نشد.',
            ]);
        }

        if ($transaction->status === SubscriptionTransaction::STATUS_SUCCESS) {
            return redirect()->route('subscription')->with([
                'payment_state' => 'success',
                'payment_message' => 'پرداخت قبلا تایید شده است.',
            ]);
        }

        if ($gatewayStatus !== 'OK') {
            $newStatus = ($transaction->expires_at && now()->greaterThan($transaction->expires_at))
                ? SubscriptionTransaction::STATUS_EXPIRED
                : SubscriptionTransaction::STATUS_CANCEL;

            if ($transaction->status === SubscriptionTransaction::STATUS_PENDING) {
                $transaction->update([
                    'status' => $newStatus,
                    'gateway_status' => $gatewayStatus,
                    'message' => $newStatus === SubscriptionTransaction::STATUS_EXPIRED
                        ? 'مهلت پرداخت منقضی شد.'
                        : 'پرداخت توسط کاربر لغو شد.',
                ]);
            }

            return redirect()->route('subscription')->with([
                'payment_state' => $newStatus,
                'payment_message' => $newStatus === SubscriptionTransaction::STATUS_EXPIRED
                    ? 'مهلت پرداخت به پایان رسیده است.'
                    : 'پرداخت لغو شد.',
            ]);
        }

        if ($authority === '') {
            $this->markAsError($transaction, null, 'اتوریتی از زرین پال دریافت نشد.');

            return redirect()->route('subscription')->with([
                'payment_state' => 'error',
                'payment_message' => 'اتوریتی تراکنش موجود نیست.',
            ]);
        }

        try {
            $verification = zarinpal()
                ->amount((int) $transaction->amount)
                ->verification()
                ->authority($authority)
                ->send();
        } catch (\Throwable $exception) {
            Log::error('Subscription payment verification failed', [
                'transaction_id' => $transaction->id,
                'authority' => $authority,
                'exception' => $exception->getMessage(),
            ]);

            $this->markAsError($transaction, null, 'خطا در بررسی تراکنش در زرین پال');

            return redirect()->route('subscription')->with([
                'payment_state' => 'error',
                'payment_message' => 'بررسی تراکنش با خطا مواجه شد. لطفا با پشتیبانی تماس بگیرید.',
            ]);
        }

        if ($verification->success()) {
            $this->markAsSuccess(
                $transaction,
                (string) $verification->referenceId(),
                $verification->cardPan(),
                100,
                'پرداخت با موفقیت تایید شد.'
            );

            return redirect()->route('subscription')->with([
                'payment_state' => 'success',
                'payment_message' => 'پرداخت با موفقیت انجام شد و اشتراک شما فعال گردید.',
            ]);
        }

        $errorCode = $verification->error()->code();
        $errorMessage = $verification->error()->message();

        if ($errorCode === -101) {
            $this->markAsSuccess(
                $transaction,
                $transaction->ref_id,
                $transaction->card_pan,
                $errorCode,
                'تراکنش قبلا تایید شده است.'
            );

            return redirect()->route('subscription')->with([
                'payment_state' => 'success',
                'payment_message' => 'تراکنش قبلا تایید شده بود و اشتراک بررسی شد.',
            ]);
        }

        $this->markAsError($transaction, $errorCode, $errorMessage);

        return redirect()->route('subscription')->with([
            'payment_state' => 'error',
            'payment_message' => 'پرداخت ناموفق بود: ' . $errorMessage,
        ]);
    }

    private function markAsSuccess(
        SubscriptionTransaction $transaction,
        ?string $refId,
        ?string $cardPan,
        int $gatewayCode,
        string $message
    ): void {
        $shouldSendSms = false;
        $smsUserId = 0;
        $smsMonths = 0;

        DB::transaction(function () use (
            $transaction,
            $refId,
            $cardPan,
            $gatewayCode,
            $message,
            &$shouldSendSms,
            &$smsUserId,
            &$smsMonths
        ) {
            $fresh = SubscriptionTransaction::query()
                ->where('id', $transaction->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($fresh->status === SubscriptionTransaction::STATUS_SUCCESS) {
                return;
            }

            $subscribeId = $fresh->subscribe_id;

            if (!$subscribeId) {
                $subscribeId = app(SubscriptionActivationService::class)->activate($fresh->user_id, $fresh->plan_months, $fresh->amount, $refId);
            }

            $fresh->update([
                'subscribe_id' => $subscribeId,
                'status' => SubscriptionTransaction::STATUS_SUCCESS,
                'gateway_status' => 'OK',
                'gateway_code' => $gatewayCode,
                'ref_id' => $refId,
                'card_pan' => $cardPan,
                'activated_at' => now(),
                'paid_at' => now(),
                'message' => $message,
            ]);

            $shouldSendSms = true;
            $smsUserId = (int) $fresh->user_id;
            $smsMonths = (int) $fresh->plan_months;
        });

        if ($shouldSendSms && $smsUserId > 0 && $smsMonths > 0) {
            app(SubscriptionActivationService::class)->sendActivationSms($smsUserId, $smsMonths);
        }
    }

    private function markAsError(SubscriptionTransaction $transaction, ?int $gatewayCode, string $message): void
    {
        if ($transaction->status !== SubscriptionTransaction::STATUS_PENDING) {
            return;
        }

        $transaction->update([
            'status' => SubscriptionTransaction::STATUS_ERROR,
            'gateway_code' => $gatewayCode,
            'message' => $message,
        ]);
    }

    private function parseManualPaidAt(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        foreach (['Y/m/d H:i', 'Y/m/d H:i:s', 'Y-m-d H:i', 'Y-m-d H:i:s'] as $format) {
            try {
                return Verta::parseFormat($format, $value, 'Asia/Tehran')
                    ->toCarbon()
                    ->format('Y-m-d H:i:s');
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }

}
