<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuditLogger
{
    public function logPageView(Request $request, Response $response): void
    {
        $this->write([
            'event' => 'page_view',
            'action' => 'page.view',
            'status_code' => $response->getStatusCode(),
            'metadata' => [
                'referer' => $request->headers->get('referer'),
                'title' => $request->route()?->getName(),
            ],
        ], $request);
    }

    public function logRequestOperation(Request $request, Response $response): void
    {
        $route = $request->route();
        $controller = $route?->getActionName();

        $this->write([
            'event' => 'operation',
            'action' => $route?->getName() ?: Str::lower($request->method()) . '.' . trim($request->path(), '/'),
            'status_code' => $response->getStatusCode(),
            'metadata' => $this->sanitize([
                'controller' => $controller !== 'Closure' ? $controller : null,
                'route_parameters' => $this->routeParameters($request),
            ]),
        ], $request);
    }

    public function logAction(
        string $action,
        ?Model $auditable = null,
        array $changes = [],
        array $metadata = [],
        ?User $actor = null,
        ?Request $request = null,
        string $event = 'action'
    ): void {
        $request ??= request();
        $actor ??= Auth::user();

        $this->write([
            'event' => $event,
            'action' => $action,
            'user_id' => $actor?->id,
            'auditable_type' => $auditable ? $auditable::class : null,
            'auditable_id' => $auditable?->getKey(),
            'changes' => $this->sanitize($changes),
            'metadata' => $this->sanitize($metadata),
        ], $request, $actor);
    }

    private function write(array $data, Request $request, ?User $actor = null): void
    {
        try {
            $actor ??= Auth::user();
            $snapshot = $this->actorSnapshot($actor);

            AuditLog::query()->create(array_merge([
                'user_id' => $actor?->id,
                'event' => 'action',
                'action' => null,
                'area' => $this->area($request),
                'method' => $request->method(),
                'route_name' => $request->route()?->getName(),
                'path' => '/' . ltrim($request->path(), '/'),
                'full_url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'status_code' => null,
                'auditable_type' => null,
                'auditable_id' => null,
                'actor_roles' => $snapshot['roles'],
                'actor_permissions' => $snapshot['permissions'],
                'changes' => null,
                'metadata' => null,
                'occurred_at' => now(),
            ], $data));
        } catch (Throwable) {
            // Audit logging must never break the user's primary workflow.
        }
    }

    private function actorSnapshot(?User $actor): array
    {
        if (!$actor) {
            return ['roles' => [], 'permissions' => []];
        }

        $roles = $actor->relationLoaded('roles')
            ? $actor->roles->where('is_active', true)
            : $actor->activeRoles()->get(['name', 'slug', 'permissions']);

        return [
            'roles' => $roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ])->values()->all(),
            'permissions' => $roles->flatMap(fn ($role) => $role->permissions ?? [])->unique()->values()->all(),
        ];
    }

    private function area(Request $request): string
    {
        $path = trim($request->path(), '/');

        if (str_starts_with($path, 'admin')) {
            return 'admin';
        }

        if (str_starts_with($path, 'support')) {
            return 'support';
        }

        return 'dashboard';
    }

    private function sanitize(array $payload): array
    {
        $hidden = ['password', 'password_confirmation', 'token', '_token', 'remember_token'];

        foreach ($payload as $key => $value) {
            if (in_array((string) $key, $hidden, true)) {
                $payload[$key] = '[FILTERED]';
                continue;
            }

            if (is_array($value)) {
                $payload[$key] = $this->sanitize($value);
            }
        }

        return $payload;
    }

    private function routeParameters(Request $request): array
    {
        return collect($request->route()?->parameters() ?? [])
            ->map(function ($value) {
                if ($value instanceof Model) {
                    return [
                        'type' => $value::class,
                        'id' => $value->getKey(),
                    ];
                }

                if (is_object($value)) {
                    return method_exists($value, '__toString') ? (string) $value : $value::class;
                }

                return $value;
            })
            ->all();
    }
}