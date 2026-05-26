<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // مسیر ویوی صفحه لاگین
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // اعتبارسنجی یوزر
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // ✅ تنها خط حیاتی و قطعی:
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'مشخصات وارد شده صحیح نیست.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
