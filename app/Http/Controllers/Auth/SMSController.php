<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OTP;
use App\Models\Notifs;
use App\Models\User;
use App\Models\Bot1;
use App\Models\Bot2;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kavenegar;
use Validator;
use Illuminate\Support\Facades\DB;


class SMSController extends Controller
{
    public function index(Request $request)
    {

        try {


            if (isset($request->password)) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'username' => ['required', 'min:4'],
                        'password' => ['required', 'min:8'],
                    ],
                    [
                        'username' => 'نام کاربری حداقل 4 حرفی میباشد.',
                        'password' => 'رمز عبور باید حداقل 8 کاراکتر داشته باشد.',
                    ]
                );
            } else {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'mobile' => ['required', 'regex:/^(09)[0-9]{9}/'],
                    ],
                    [
                        'mobile' => 'شماره موبایل باید 11 رقم و با 09 شروع شود',
                    ]
                );
            }


            if ($validator->fails()) {
                return redirect(route('login'))->withErrors($validator);
            }

            if (isset($request->password)) {
                $HashPass = Hash::make($request->password);
                $user = User::where('username', $request->username)->first();

                if ($user &&  Hash::check($request->password, $user->password)) {
                    //$token = $user->createToken('auth_token')->plainTextToken;

                    // $user->app_token = $token;
                    // $user->update();

                    Auth::login($user);

                    $Notif = new Notifs();
                    $Notif->user_id = auth()->user()->id;
                    $Notif->title = "ورود موفقیت آمیز";
                    $Notif->content = "گزارش ورود شما با موفقیت ثبت شد.";
                    $Notif->save();

                    return redirect(route('dashboard'))->with('successLogin', true);
                } else {
                    $msg = 'اطلاعات معتبر نیست.';
                    return redirect(route('login'))->with('error', $msg);
                }
            }

            $user = User::where('mobile', $request->mobile)->first();
            if ($user == null) {

                $user = new User();
                $user->mobile = $request->mobile;
                //$user->assignRole('clinet');
                //$user->password = Hash::make($generatedPassword);

                $user->save();
                
                $OTP = rand(100000, 999999);

                $receptor = $request->mobile;        //This is the Sender number
                $template = "roohiBotOtp";
                $type = "sms";
                $token = $OTP;
                $token2 = "";
                $token3 = "";
                $result = Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type);

                $OTP = OTP::create([
                    'mobile' => $request->mobile,
                    'otp' => $OTP,
                    'register_date' => date('Y-m-d H:i:s'),
                ]);

                if (empty($user->referrer_id)) {

                    $refCode = session('ref_code');
                    
                    if ($refCode) {
                        
                        $referrer = User::where('ref_code', $refCode)->first();
                        

                        if ($referrer && $referrer->id != $user->id) {
                           
                            $user->referrer_id = $referrer->id;
                            $user->save();

                            $maskedMobile = substr($request->mobile, 0, 4) . '****' . substr($request->mobile, -2);
                            $Notif = new Notifs();
                            $Notif->user_id = $referrer->id;
                            $Notif->title = "عضو جدید با کد معرف شما ثبت نام کرد";
                            $Notif->content = "$referrer->name عزیز، کاربر جدیدی با شماره همراه <span style='display: inline-block;direction: ltr;'>$maskedMobile</span> با استفاده از کد معرف شما ثبت نام کرد. ";
                            $Notif->save();
                        }
                    }
                }

                session(['mobile_otp' => $request->mobile]);
                return redirect(route('otpPage'))->with('success', "کد ارسال شده به شماره $request->mobile را وارد کنید.");
            } elseif ($user) {

                if ($user->status == 99) {
                    $msg = 'این حساب کاربری مسدود شده است. با پشتیبانی تماس بگیرید.';
                    return redirect(route('login'))->with('error', $msg);
                }

                $GET_OTP = OTP::where('mobile', $request->get('mobile'))->orderBy('id', 'desc')->first();
                if ($GET_OTP) {
                    $date = $GET_OTP->register_date;
                    $diff = Carbon::parse($date)->diffInMinutes();
                    if ($diff > 2) {

                        $OTP = rand(100000, 999999);

                        $receptor = $request->mobile;        //This is the Sender number
                        $template = "roohiBotOtp";
                        $type = "sms";
                        $token = $OTP;
                        $token2 = "";
                        $token3 = "";
                        $result = Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type);

                        $OTP = OTP::create([
                            'mobile' => $request->mobile,
                            'otp' => $OTP,
                            'register_date' => date('Y-m-d H:i:s'),
                        ]);

                        session(['mobile_otp' => $request->mobile]);
                        return redirect(route('otpPage'))->with('success', "کد ارسال شده به شماره $request->mobile را وارد کنید.");
                    } else {

                        $msg = 'کد تایید قبلی شما هنوز معتبر است';
                        return redirect(route('otpPage'))->with('error', $msg);
                    }
                } else {

                    // last otp code not isset and new generated
                    $OTP = rand(100000, 999999);

                    $receptor = $request->mobile;        //This is the Sender number
                    $template = "roohiBotOtp";
                    $type = "sms";
                    $token = $OTP;
                    $token2 = "";
                    $token3 = "";
                    $result = Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type);

                    $OTP = OTP::create([
                        'mobile' => $request->mobile,
                        'otp' => $OTP,
                        'register_date' => date('Y-m-d H:i:s'),
                    ]);

                    session(['mobile_otp' => $request->mobile]);
                    return redirect(route('otpPage'))->with('success', "کد ارسال شده به شماره $request->mobile را وارد کنید.");
                }
            } else {
                $msg = 'اطلاعات معتبر نیست.';
                return redirect(route('login'))->with('error', $msg);
            }
        } catch (\Throwable $error) {
            $msg = 'کد تایید قبلی شما هنوز معتبر است';
            return redirect(route('login'))->with('error', $error->getMessage());
        }
    }

    public function otp_page()
    {
        if (session()->has('mobile_otp')) {
            return view('auth.otp');
        } else {
            return redirect(route('login'));
        }
    }

    public function vilidation_code(Request $request)
    {


        $otp = implode('',  $request->otp);

        $mobile = session('mobile_otp');


        $sms_before = OTP::where('mobile', $mobile)->where('status', 0)->orderBy('id', 'desc')->first();

        if ($sms_before == null || Carbon::parse($sms_before->register_date)->diffInMinutes(now()) > 2) {

            return redirect(route('login'))->with('error', 'کد وارد شده منقضی شده است.');
        } else if ($sms_before->otp != $otp) {
            return redirect(route('otpPage'))->with('error', 'کد وارد شده صحیح نمیباشد.');
        } elseif ($sms_before->otp == $otp) {
            $User = User::where('mobile', $mobile)->first();
            $sms_before = OTP::where('mobile', $mobile)->where('status', 0)->update(['status' => 1]);
            Auth::login($User);



            /* $token = $User->createToken('auth_token')->plainTextToken;
            $User->app_token = $token;
            $User->update(); */

            $SelectSub = Subscribe::where('user_id',$User->id)->count();
            if($SelectSub == 0) {

                $now = Carbon::now();
                $selectBot1 = Bot1::where('mobile', $User->mobile)->where('status',1)->where('vip', '>', 0)->first();
                $selectBot2 = Bot2::where('mobile', $User->mobile)->where('status',1)->where('vip', '>', 0)->first();
                if($selectBot1 || $selectBot2) {
                    $subscribe = new Subscribe();
                    $subscribe->user_id = $User->id;
                    $subscribe->type = 3; // inserted from telegram
                    $subscribe->vip = 1;
                   
                    if ($selectBot1 && !empty($selectBot1->exp_vip)) {
                        $subscribe->start_vip = $selectBot1->start_vip;
                        $subscribe->exp_vip   = Carbon::parse($selectBot1->exp_vip)->addMonth();
                    }elseif ($selectBot2 && !empty($selectBot2->exp_vip)) {
                        $subscribe->start_vip = $now;
                        $subscribe->exp_vip   = $now->copy()->addMonth();
                    }else {
                        $subscribe->start_vip = $selectBot1->start_vip;
                        $subscribe->exp_vip   = Carbon::parse($selectBot1->exp_vip)->addMonth();
                    }
                    $subscribe->register_date = now();
                    $subscribe->method = 0;
                    $subscribe->status = 1;
                    $subscribe->save();

                }

            }
            if (empty($User->name) || empty($User->username)) {

                $selectBot1 = Bot1::where('mobile', $User->mobile)->first();

                $selectBot2 = Bot2::where('mobile', $User->mobile)->first();

                $source = $selectBot1 ?: $selectBot2;

                if ($source) {
                    // فقط فیلدهایی که خالی هستند را پر کن (که اطلاعات قبلی کاربر خراب نشه)
                    if (empty($User->name))      $User->name = $source->name;
                    if (empty($User->last_name)) $User->last_name = $source->last_name;
                    if (empty($User->username)) $User->username = $source->username;
                    if (empty($User->nam))      $User->nam = $source->nam;

                    if($selectBot2) {
                        if (empty($User->lbank_uid))      $User->lbank_uid = $selectBot2->lbank_uid;
                    }

                    $User->save(); // ✅ اینجا باید save بشه، نه update بدون آرگومان
                    $User->refresh(); // برای اطمینان از اینکه آخرین دیتا از DB برگشته
                }

            }

            $Notif = new Notifs();
            $Notif->user_id = auth()->user()->id;
            $Notif->title = "ورود موفقیت آمیز";
            $Notif->content = "گزارش ورود شما با موفقیت ثبت شد.";
            $Notif->save();


            return redirect(route('dashboard'))->with('successLogin', true);
        }
    }

    public function resendOtp()
    {
        $mobile = session('mobile_otp');
        // last otp code not isset and new generated
        $OTP = rand(100000, 999999);

        $receptor = $mobile;        //This is the Sender number
        $template = "roohiBotOtp";
        $type = "sms";
        $token = $OTP;
        $token2 = "";
        $token3 = "";
        $result = Kavenegar::VerifyLookup($receptor, $token, $token2, $token3, $template, $type);

        $OTP = OTP::create([
            'mobile' => $mobile,
            'otp' => $OTP,
            'register_date' => date('Y-m-d H:i:s'),
        ]);

        return redirect(route('otp_page'))->with('success', "کد ارسال شده به شماره $mobile را وارد کنید.");
    }
}
