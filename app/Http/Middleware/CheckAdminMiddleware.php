<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMiddleware   // <-- این باید تصحیح شود
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ((int) $user->isAdmin === 1) {
            return $next($request);
        }

        $salesTeamRoutes = [
            'users.index',
            'users.all',
            'users.activeUsers',
            'users.deactiveUsers',
            'users.leftUsers',
            'users.data',
            'users.search',
            'users.detail',
            'users.bulkGroups',
        ];

        if ($request->routeIs(...$salesTeamRoutes) && ($user->supportGroups()->exists() || $user->hasRole('sales-manager') || $user->hasRole('sales-expert'))) {
            return $next($request);
        }

        if ((int) $user->isAdmin !== 1) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
