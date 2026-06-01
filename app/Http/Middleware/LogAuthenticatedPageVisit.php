<?php

namespace App\Http\Middleware;

use App\Services\AuditLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAuthenticatedPageVisit
{
    public function __construct(private AuditLogger $auditLogger)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldLogPageView($request, $response)) {
            $this->auditLogger->logPageView($request, $response);
        } elseif ($this->shouldLogOperation($request, $response)) {
            $this->auditLogger->logRequestOperation($request, $response);
        }

        return $response;
    }

    private function shouldLogPageView(Request $request, Response $response): bool
    {
        return auth()->check()
            && $request->isMethod('GET')
            && !$request->expectsJson()
            && !$request->ajax()
            && $response->getStatusCode() < 500;
    }

    private function shouldLogOperation(Request $request, Response $response): bool
    {
        return auth()->check()
            && !$request->isMethod('GET')
            && !$request->isMethod('HEAD')
            && !$request->isMethod('OPTIONS')
            && $response->getStatusCode() < 500;
    }
}