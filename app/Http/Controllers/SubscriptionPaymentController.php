<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use App\Models\SubscriptionTransaction;
use App\Services\VipActivationSmsService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SubscriptionPaymentController extends Controller
{
    private const CALLBACK_EXPIRE_MINUTES = 20;

    private const PLANS = [
        1 => [
            'months' => 1,
            'amount' => 4900000,
            'title' => 'اشتراک یک ماهه',
        ],
        2 => [
            'months' => 2,
            'amount' => 8800000,
            'title' => 'اشتراک دو ماهه',
        ],
        3 => [
            'months' => 3,
            'amount' => 11700000,
            'title' => 'اشتراک سه ماهه',
        ],
    ];

    public function request(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => 'required|integer|in:1,2,3',
        ]);

        $plan = self::PLANS[(int) $validated['plan']];
        $user = $request->user();

        $transaction = SubscriptionTransaction::query()->create([
            'user_id' => $user->id,
            'plan_months' => $plan['months'],
            'amount' => $plan['amount'],
            'currency' => (string) config('zarinpal.currency', 'IRT'),
            'status' => SubscriptionTransaction::STATUS_PENDING,
            'message' => 'در انتظار پرداخت کاربر',
            'expires_at' => now()->addMinutes(self::CALLBACK_EXPIRE_MINUTES),
        ]);

        $callbackUrl = route('subscription.payment.callback', [
            'tx' => $transaction->id,
        ]);

        try {
            $gateway = zarinpal()
                ->amount((int) $plan['amount'])
                ->request()
                ->description($plan['title'] . ' - کاربر #' . $user->id)
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
        $smsDays = 0;

        DB::transaction(function () use (
            $transaction,
            $refId,
            $cardPan,
            $gatewayCode,
            $message,
            &$shouldSendSms,
            &$smsUserId,
            &$smsDays
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
                $subscribeId = $this->activateSubscription($fresh->user_id, $fresh->plan_months, $fresh->amount, $refId);
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
            $smsDays = max(0, (int) $fresh->plan_months * 30);
        });

        if ($shouldSendSms && $smsUserId > 0 && $smsDays > 0) {
            app(VipActivationSmsService::class)->sendByUserId($smsUserId, $smsDays);
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

    private function activateSubscription(int $userId, int $planMonths, int $amount, ?string $trackingCode): int
    {
        $now = now();

        $activeSub = Subscribe::query()
            ->where('user_id', $userId)
            ->where('status', 1)
            ->whereNotNull('exp_vip')
            ->orderByDesc('exp_vip')
            ->first();

        $extendFrom = ($activeSub && $activeSub->exp_vip && Carbon::parse($activeSub->exp_vip)->isFuture())
            ? Carbon::parse($activeSub->exp_vip)
            : $now->copy();

        $endDate = $extendFrom->copy()->addMonths($planMonths);

        Subscribe::query()
            ->where('user_id', $userId)
            ->where('status', 1)
            ->update(['status' => 0]);

        $subscribe = new Subscribe();
        $subscribe->user_id = $userId;
        $subscribe->vip = $planMonths;
        $subscribe->start_vip = $now;
        $subscribe->exp_vip = $endDate;
        $subscribe->type = 3;
        $subscribe->register_date = $now;
        $subscribe->method = 1;
        $subscribe->status = 1;

        if (Schema::hasColumn('subscribes', 'price')) {
            $subscribe->price = $amount;
        }

        if (Schema::hasColumn('subscribes', 'tracking_code')) {
            $subscribe->tracking_code = $trackingCode;
        }

        if (Schema::hasColumn('subscribes', 'created_at')) {
            $subscribe->created_at = $now;
        }

        if (Schema::hasColumn('subscribes', 'updated_at')) {
            $subscribe->updated_at = $now;
        }

        $subscribe->save();

        return (int) $subscribe->id;
    }
}
