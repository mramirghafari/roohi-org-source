<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Models\RemoteEntryToken;  // وقتی جدول توکن را وصل کردیم

class RemoteKioskController extends Controller
{
    /**
     * این endpoint را Nginx با auth_request صدا می‌زند.
     * IMPORTANT: اینجا redirect ممنوع است.
     * فقط 204 (مجاز) یا 401 (غیرمجاز).
     */
    public function auth(Request $request)
    {
        // روش 1 (فعلاً): فقط با کوکی rk اجازه بده (بعداً پیاده می‌کنیم)
        $rk = $request->cookie('rk');
        if (!$rk) {
            return response('Unauthorized', 401);
        }

        // TODO: اعتبارسنجی rk در دیتابیس (session/token)
        // اگر معتبر بود:
        return response('', 204);

        // اگر نامعتبر بود:
        // return response('Unauthorized', 401);
    }

    /**
     * این را کاربر در مرورگر باز می‌کند.
     * اینجا می‌تونی redirect به ثبت‌نام/لاگین بدهی.
     */
    public function access(Request $request, string $token)
    {
        // اگر کاربر عضو/لاگین نیست => redirect به عضویت/لاگین
        if (!Auth::check()) {
            return redirect()->to('/register'); // یا /login یا صفحه عضویت خودت
        }

        // TODO: اینجا توکن یک‌بار مصرف را در DB چک کن
        // اگر توکن معتبر نبود:
        // return redirect()->to('/register');

        // اگر معتبر بود:
        // 1) یک rk بساز/ست کن
        // 2) redirect به /vnc.html روی همین ساب‌دامین

        return redirect('/vnc.html')
            ->withCookie(cookie('rk', 'TEMP_RK_VALUE', 60, '/', null, true, true, false, 'Lax'));
    }
}
