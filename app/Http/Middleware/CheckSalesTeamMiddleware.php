<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSalesTeamMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ((int) $user->isAdmin === 1
            || $user->supportGroups()->exists()
            || $user->hasRole('marketer')
            || $user->hasRole('sales-expert')
            || $user->hasRole('sales-manager')) {
            return $next($request);
        }

        return redirect()->route('dashboard');
    }
}