<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use App\Models\Subscribe;


class CheckVipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && (int) $user->isAdmin === 1) {
            return $next($request);
        }

        if (auth()->user()->has_active_vip == null ) {
            return redirect()->route('dashboard')->with('vip_error', 'برای دسترسی به کانال VIP لطفا اشتراک تهیه کنید.');
        }

        $sub = Subscribe::query()
            ->where('user_id', auth()->user()->id)
            ->where('status', 1)
            ->orderByDesc('exp_vip')   // یا created_at / id
            ->first();

        $StartDate = $sub?->start_vip ? Carbon::parse($sub->start_vip) : null;
        $EndDate   = $sub?->exp_vip   ? Carbon::parse($sub->exp_vip)   : null;
        
        if ($EndDate && $EndDate->isPast()) {

            return redirect()->route('dashboard')->with('vip_error', 'مدت اشتراک VIP شما به پایان رسیده است.');

        }

        if(auth()->user()->lbank_uid == null) {
            
           // return redirect()->route('dashboard.profile.uid')->with('error', 'برای استفاده از کانال VIP لطفا UID ال بانک خود را ثبت کنید.');


        }

        return $next($request);
    }
}
