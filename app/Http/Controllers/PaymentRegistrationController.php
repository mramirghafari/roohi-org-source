<?php

namespace App\Http\Controllers;

use App\Models\PaymentRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentRegistrationController extends Controller
{
    private const AMOUNT = 4900000;

    private const CALLBACK_EXPIRE_MINUTES = 20;

    public function show(): View
    {
        return view('payment', [
            'amount' => self::AMOUNT,
        ]);
    }

    public function request(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'min:3', 'max:150', 'regex:/^(?!.*[0-9۰-۹])[\p{Arabic}\x{200C}\s]+$/u'],
            'mobile' => ['required', 'string', 'regex:/^09[0-9]{9}$/'],
        ], [
            'full_name.required' => 'نام و نام خانوادگی الزامی است.',
            'full_name.min' => 'نام و نام خانوادگی باید حداقل 3 کاراکتر باشد.',
            'full_name.regex' => 'نام و نام خانوادگی باید فقط با حروف فارسی وارد شود.',
            'mobile.required' => 'شماره موبایل الزامی است.',
            'mobile.regex' => 'شماره موبایل باید با 09 شروع شود و 11 رقم باشد.',
        ]);

        $registration = PaymentRegistration::query()->create([
            'full_name' => (string) $validated['full_name'],
            'mobile' => (string) $validated['mobile'],
            'amount' => self::AMOUNT,
            'currency' => (string) config('zarinpal.currency', 'IRT'),
            'tracking_code' => $this->generateTrackingCode(),
            'status' => PaymentRegistration::STATUS_PENDING,
            'message' => 'در انتظار پرداخت کاربر',
            'expires_at' => now()->addMinutes(self::CALLBACK_EXPIRE_MINUTES),
        ]);

        $callbackUrl = route('payment.callback', [
            'tx' => $registration->id,
        ]);

        try {
            $gateway = zarinpal()
                ->amount(self::AMOUNT)
                ->request()
                ->description('ثبت نام روحی ترید - ' . $registration->full_name . ' - ' . $registration->tracking_code)
                ->callbackUrl($callbackUrl)
                ->mobile($registration->mobile);

            $response = $gateway->send();
        } catch (\Throwable $exception) {
            Log::error('Public payment request failed', [
                'registration_id' => $registration->id,
                'exception' => $exception->getMessage(),
            ]);

            $registration->update([
                'status' => PaymentRegistration::STATUS_ERROR,
                'message' => 'خطا در ارتباط با درگاه پرداخت',
            ]);

            return redirect()->route('payment.form')->with([
                'payment_state' => 'error',
                'payment_message' => 'ارتباط با درگاه پرداخت برقرار نشد. لطفا دوباره تلاش کنید.',
            ])->withInput();
        }

        if (!$response->success()) {
            $registration->update([
                'status' => PaymentRegistration::STATUS_ERROR,
                'gateway_code' => $response->error()->code(),
                'message' => $response->error()->message(),
            ]);

            return redirect()->route('payment.form')->with([
                'payment_state' => 'error',
                'payment_message' => 'ارسال به درگاه ناموفق بود: ' . $response->error()->message(),
            ])->withInput();
        }

        $authority = method_exists($response, 'authority') ? (string) $response->authority() : null;

        $registration->update([
            'authority' => $authority ?: null,
            'gateway_code' => 100,
            'message' => 'ارسال به درگاه انجام شد',
        ]);

        return $response->redirect() ?? redirect()->route('payment.form')->with([
            'payment_state' => 'error',
            'payment_message' => 'هدایت به درگاه انجام نشد. دوباره تلاش کنید.',
        ]);
    }

    public function callback(Request $request): RedirectResponse
    {
        $authority = (string) $request->query('Authority', '');
        $gatewayStatus = strtoupper((string) $request->query('Status', ''));
        $registrationId = (int) $request->query('tx');

        if ($registrationId <= 0) {
            return redirect()->route('payment.form')->with([
                'payment_state' => 'error',
                'payment_message' => 'شناسه تراکنش نامعتبر است.',
            ]);
        }

        $registration = PaymentRegistration::query()->find($registrationId);

        if (!$registration) {
            return redirect()->route('payment.form')->with([
                'payment_state' => 'error',
                'payment_message' => 'تراکنش مورد نظر پیدا نشد.',
            ]);
        }

        if ($authority !== '' && !$registration->authority) {
            $registration->update([
                'authority' => $authority,
            ]);
        }

        if ($registration->status === PaymentRegistration::STATUS_SUCCESS) {
            return $this->successRedirect($registration, 'پرداخت قبلا تایید شده است.');
        }

        if ($gatewayStatus !== 'OK') {
            $newStatus = ($registration->expires_at && now()->greaterThan($registration->expires_at))
                ? PaymentRegistration::STATUS_EXPIRED
                : PaymentRegistration::STATUS_CANCEL;

            if ($registration->status === PaymentRegistration::STATUS_PENDING) {
                $registration->update([
                    'status' => $newStatus,
                    'gateway_status' => $gatewayStatus,
                    'message' => $newStatus === PaymentRegistration::STATUS_EXPIRED
                        ? 'مهلت پرداخت منقضی شد.'
                        : 'پرداخت توسط کاربر لغو شد.',
                ]);
            }

            return redirect()->route('payment.form')->with([
                'payment_state' => $newStatus,
                'payment_message' => $newStatus === PaymentRegistration::STATUS_EXPIRED
                    ? 'مهلت پرداخت به پایان رسیده است.'
                    : 'پرداخت لغو شد.',
            ]);
        }

        if ($authority === '') {
            $this->markAsError($registration, null, 'اتوریتی از زرین پال دریافت نشد.');

            return redirect()->route('payment.form')->with([
                'payment_state' => 'error',
                'payment_message' => 'اتوریتی تراکنش موجود نیست.',
            ]);
        }

        try {
            $verification = zarinpal()
                ->amount((int) $registration->amount)
                ->verification()
                ->authority($authority)
                ->send();
        } catch (\Throwable $exception) {
            Log::error('Public payment verification failed', [
                'registration_id' => $registration->id,
                'authority' => $authority,
                'exception' => $exception->getMessage(),
            ]);

            $this->markAsError($registration, null, 'خطا در بررسی تراکنش در زرین پال');

            return redirect()->route('payment.form')->with([
                'payment_state' => 'error',
                'payment_message' => 'بررسی تراکنش با خطا مواجه شد. لطفا با پشتیبانی تماس بگیرید.',
            ]);
        }

        if ($verification->success()) {
            $registration->update([
                'status' => PaymentRegistration::STATUS_SUCCESS,
                'gateway_status' => 'OK',
                'gateway_code' => 100,
                'ref_id' => (string) $verification->referenceId(),
                'card_pan' => $verification->cardPan(),
                'paid_at' => now(),
                'message' => 'پرداخت با موفقیت تایید شد.',
            ]);

            return $this->successRedirect($registration->fresh(), 'ثبت نام شما با موفقیت انجام شد.');
        }

        $errorCode = $verification->error()->code();
        $errorMessage = $verification->error()->message();

        if ($errorCode === -101) {
            $registration->update([
                'status' => PaymentRegistration::STATUS_SUCCESS,
                'gateway_status' => 'OK',
                'gateway_code' => $errorCode,
                'paid_at' => $registration->paid_at ?? now(),
                'message' => 'تراکنش قبلا تایید شده است.',
            ]);

            return $this->successRedirect($registration->fresh(), 'ثبت نام شما با موفقیت انجام شد.');
        }

        $this->markAsError($registration, $errorCode, $errorMessage);

        return redirect()->route('payment.form')->with([
            'payment_state' => 'error',
            'payment_message' => 'پرداخت ناموفق بود: ' . $errorMessage,
        ]);
    }

    private function markAsError(PaymentRegistration $registration, ?int $gatewayCode, string $message): void
    {
        if ($registration->status !== PaymentRegistration::STATUS_PENDING) {
            return;
        }

        $registration->update([
            'status' => PaymentRegistration::STATUS_ERROR,
            'gateway_code' => $gatewayCode,
            'message' => $message,
        ]);
    }

    private function successRedirect(PaymentRegistration $registration, string $message): RedirectResponse
    {
        return redirect()->route('payment.form')->with([
            'payment_state' => 'success',
            'payment_message' => $message,
            'payment_tracking_code' => $registration->tracking_code,
            'payment_ref_id' => $registration->ref_id,
        ]);
    }

    private function generateTrackingCode(): string
    {
        do {
            $code = 'RT' . now()->format('YmdHis') . random_int(1000, 9999);
        } while (PaymentRegistration::query()->where('tracking_code', $code)->exists());

        return $code;
    }
}
