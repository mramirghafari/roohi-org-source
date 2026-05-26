<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'mt',
            'mt/*',
            'terminal',
            'terminal/*',
        ]);

        $middleware->alias([
            'checkAdmin' => \App\Http\Middleware\CheckAdminMiddleware::class,
            'checkVip' => \App\Http\Middleware\CheckVipMiddleware::class,
            'checkSupport' => \App\Http\Middleware\CheckSupportMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
