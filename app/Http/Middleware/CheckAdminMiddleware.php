<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMiddleware   // <-- این باید تصحیح شود
{
    public function handle($request, Closure $next)
    {
        if (auth()->user()->isAdmin != 1) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
