<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\UserApiLbank;
use App\Models\User;
use App\Services\LbankUserInfoService;

class LbankApiController extends Controller
{
    public function store(Request $request)
    {

       
        $request->validate([
            'api_key'    => ['required', 'string'],
            'api_secret' => ['required', 'string'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 1. Call API key validation service
        |--------------------------------------------------------------------------
        */
        $response = Http::asForm()
            ->timeout(15)
            ->post('https://roohi.trade/lbank/check_api.php', [
                'api_key'    => $request->api_key,
                'api_secret' => $request->api_secret,
            ]);

        if (!$response->ok()) {
            return back()->with('error', 'خطا در ارتباط با سرور ولت ال بانک. لطفا مجددا تلاش کنید.');
        }

        $result = $response->json();

        /*
        |--------------------------------------------------------------------------
        | 2. If key is NOT valid → reject
        |--------------------------------------------------------------------------
        */
        if (
            !isset($result['valid']) ||
            $result['valid'] !== true
        ) {
            return back()->with('error', 'کلیدهای API وارد شده معتبر نیستند. لطفا مجددا بررسی کنید.');
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Key is valid → store service
        |--------------------------------------------------------------------------
        */
        UserApiLbank::updateOrCreate(
            [
                // شرط یکتایی
                'user_id' => auth()->id(),
            ],
            [
                // مقادیر قابل آپدیت
                'api_key'      => $request->api_key,
                'api_secret'   => $request->api_secret, // حتماً encrypt
                'is_connected' => 1,
            ]
        );

        return redirect()
            ->route('autoTradeSetting')
            ->with('success', 'کلیدهای API با موفقیت ثبت شد');
    }


    public function lbank_checkBalance_process(Request $request, LbankUserInfoService $lbankUserInfoService)
    {
        // ✅ اعتبارسنجی ورودی
        $request->validate([
            'uid' => ['required', 'string'],
        ]);

        $uid = $request->input('uid');

        $result = $lbankUserInfoService->fetchByUid($uid);

        if (!$result['ok']) {
            return back()->withErrors([
                'error' => $result['message'],
            ]);
        }

        // ✅ استخراج فیلدها
        $success = $result['success'];
        $inTeam = $result['inTeam'];
        $deposit = $result['deposit'];
        $trade = $result['trade'];
        $kycStatus = $result['kycStatus'];
        $spot = $result['spot'];
        $futures = $result['futures'];
        $total = $result['total'];
        $bonus = $result['bonus'];
        $spotUsdt = $result['spotUsdt'];


        $UserBot = User::where('lbank_uid', $uid)->first();

        // ✅ ارسال به Blade
        return view('dashboard.lbank_checkBalance', compact(
            'uid',
            'success',
            'inTeam',
            'deposit',
            'trade',
            'kycStatus',
            'spot',
            'futures',
            'total',
            'bonus',
            'spotUsdt',
            'UserBot',
        ));
    }


}
