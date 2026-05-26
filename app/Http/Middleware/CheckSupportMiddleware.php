<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSupportMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || ((int) $user->isAdmin !== 1 && (int) $user->is_support !== 1)) {
            return redirect()->route('dashboard')->with('error', 'شما دسترسی لازم برای بخش پشتیبانی را ندارید.');
        }

        return $next($request);
    }
}
