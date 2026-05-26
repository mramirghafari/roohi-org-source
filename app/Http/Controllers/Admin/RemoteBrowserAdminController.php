<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RemoteAccessToken;
use App\Models\RemoteInstance;
use App\Services\RemoteDesktopManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;


class RemoteBrowserAdminController extends Controller
{
    public function issue(Request $req, $userId, RemoteDesktopManager $remoteDesktopManager)
    {
        $MainUser = User::find(intval($userId));
        abort_unless($MainUser, 404);

        $validated = $req->validate([
            'remote_username' => 'required|string|min:3|max:120',
            'remote_password' => 'nullable|string|min:4|max:200',
            'remote_status' => 'required|in:0,1',
            'remote_ttl_minutes' => 'nullable|integer|min:30|max:43200',
            'remote_profile' => 'nullable|in:lightweight,balanced,performance',
            'remote_cpus' => 'nullable|numeric|min:0.5|max:4',
            'remote_memory_mb' => 'nullable|integer|min:768|max:4096',
            'remote_memory_swap_mb' => 'nullable|integer|min:1024|max:6144',
            'remote_shm_mb' => 'nullable|integer|min:128|max:1024',
            'remote_pids_limit' => 'nullable|integer|min:128|max:1024',
        ]);

        $service = config('remote_desktop.service', 'mt_terminal');
        $ttlMinutes = (int) ($validated['remote_ttl_minutes'] ?? config('remote_desktop.default_ttl_minutes', 43200));
        $desktopProfile = (string) ($validated['remote_profile'] ?? config('remote_desktop.default_profile', 'balanced'));
        $fallbackPassword = config('remote_desktop.password_fallback', 'mobile') === 'mobile'
            ? (string) ($MainUser->mobile ?: $validated['remote_username'])
            : $validated['remote_username'];

        $token = RemoteAccessToken::query()->firstOrNew([
            'user_id' => (int) $userId,
            'service' => $service,
        ]);

        $resourceOverrides = array_filter([
            'cpus' => $validated['remote_cpus'] ?? null,
            'memory_mb' => $validated['remote_memory_mb'] ?? null,
            'memory_swap_mb' => $validated['remote_memory_swap_mb'] ?? null,
            'shm_mb' => $validated['remote_shm_mb'] ?? null,
            'pids_limit' => $validated['remote_pids_limit'] ?? null,
        ], static fn ($value) => $value !== null && $value !== '');

        $token->fill([
            'token' => $token->token ?: Str::random(64),
            'username' => $validated['remote_username'],
            'password' => $validated['remote_password'] ?: $fallbackPassword,
            'is_enabled' => ((int) $validated['remote_status']) === 1,
            'expires_at' => now()->addMinutes($ttlMinutes),
            'created_by' => auth()->id(),
            'meta' => array_merge((array) $token->meta, [
                'ttl_minutes' => $ttlMinutes,
                'issued_by' => auth()->id(),
                'desktop_profile' => $desktopProfile,
                'resource_overrides' => $resourceOverrides,
            ]),
        ]);
        $token->save();

        if (!$token->is_enabled) {
            $remoteDesktopManager->stopAllForToken($token);

            return back()->with('ok', 'دسترسی ریموت کاربر غیرفعال شد.');
        }

        // Always recycle running instance after credential/profile updates
        // so the next session uses the latest token settings.
        $remoteDesktopManager->stopAllForToken($token);

        $launchRoute = route('remote.access', $token->token);

        return back()
            ->with('ok', 'دسترسی ریموت کاربر با موفقیت بروزرسانی شد.')
            ->with('issued_urls', $launchRoute);
    }

    public function stats(Request $request, $userId)
    {
        $user = User::find(intval($userId));
        abort_unless($user, 404);

        $service = config('remote_desktop.service', 'mt_terminal');
        $token = RemoteAccessToken::query()
            ->where('user_id', $user->id)
            ->where('service', $service)
            ->latest('id')
            ->first();

        if (!$token) {
            return response()->json([
                'ok' => true,
                'running' => false,
                'message' => 'no_remote_token',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $instance = RemoteInstance::query()
            ->where('remote_access_token_id', $token->id)
            ->where('status', 'running')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        if (!$instance || empty($instance->container_name)) {
            return response()->json([
                'ok' => true,
                'running' => false,
                'message' => 'no_running_instance',
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $stats = $this->dockerStats((string) $instance->container_name);
        if (!$stats) {
            return response()->json([
                'ok' => true,
                'running' => false,
                'message' => 'container_not_reachable',
                'container_name' => $instance->container_name,
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        $hostConfig = $this->dockerHostConfig((string) $instance->container_name);

        return response()->json([
            'ok' => true,
            'running' => true,
            'timestamp' => now()->toIso8601String(),
            'container_name' => $instance->container_name,
            'instance' => $instance->instance,
            'access_url' => $instance->access_url,
            'cpu_percent' => $this->parsePercent((string) ($stats['CPUPerc'] ?? '0%')),
            'memory_percent' => $this->parsePercent((string) ($stats['MemPerc'] ?? '0%')),
            'memory_usage' => (string) ($stats['MemUsage'] ?? '0B / 0B'),
            'net_io' => (string) ($stats['NetIO'] ?? '0B / 0B'),
            'block_io' => (string) ($stats['BlockIO'] ?? '0B / 0B'),
            'pids' => (int) ($stats['PIDs'] ?? 0),
            'limits' => [
                'cpus' => $hostConfig['NanoCpus'] > 0 ? round($hostConfig['NanoCpus'] / 1000000000, 2) : null,
                'memory_bytes' => (int) ($hostConfig['Memory'] ?? 0),
                'memory_swap_bytes' => (int) ($hostConfig['MemorySwap'] ?? 0),
                'shm_bytes' => (int) ($hostConfig['ShmSize'] ?? 0),
                'pids_limit' => isset($hostConfig['PidsLimit']) ? (int) $hostConfig['PidsLimit'] : null,
            ],
            'top_processes' => $request->boolean('detail') ? $this->topProcesses((string) $instance->container_name) : [],
        ]);
    }

    public function monitor($userId)
    {
        $user = User::find(intval($userId));
        abort_unless($user, 404);

        return view('dashboard.remoteStatsMonitor', [
            'user' => $user,
            'statsUrl' => route('admin.remote.stats', $user->id),
        ]);
    }

    private function dockerStats(string $containerName): ?array
    {
        $process = new Process([
            'docker',
            'stats',
            '--no-stream',
            '--format',
            '{{json .}}',
            $containerName,
        ]);
        $process->setTimeout(8);
        $process->run();

        if (!$process->isSuccessful()) {
            return null;
        }

        $raw = trim($process->getOutput());
        if ($raw === '') {
            return null;
        }

        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : null;
    }

    private function dockerHostConfig(string $containerName): array
    {
        $process = new Process([
            'docker',
            'inspect',
            '--format',
            '{{json .HostConfig}}',
            $containerName,
        ]);
        $process->setTimeout(8);
        $process->run();

        if (!$process->isSuccessful()) {
            return [];
        }

        $decoded = json_decode(trim($process->getOutput()), true);
        return is_array($decoded) ? $decoded : [];
    }

    private function parsePercent(string $value): float
    {
        if (preg_match('/([0-9]+(?:\.[0-9]+)?)/', $value, $m)) {
            return (float) $m[1];
        }

        return 0.0;
    }

    private function topProcesses(string $containerName): array
    {
        $process = new Process([
            'docker',
            'exec',
            $containerName,
            '/bin/sh',
            '-lc',
            'ps -eo pid,comm,%cpu,%mem --sort=-%cpu | head -n 10',
        ]);
        $process->setTimeout(8);
        $process->run();

        if (!$process->isSuccessful()) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', trim($process->getOutput()));
        if (!$lines || count($lines) < 2) {
            return [];
        }

        $items = [];
        foreach (array_slice($lines, 1) as $line) {
            $line = trim((string) $line);
            if ($line === '') {
                continue;
            }

            $parts = preg_split('/\s+/', $line);
            if (count($parts) < 4) {
                continue;
            }

            $items[] = [
                'pid' => (int) $parts[0],
                'command' => (string) $parts[1],
                'cpu_percent' => (float) $parts[2],
                'mem_percent' => (float) $parts[3],
            ];
        }

        return array_values(array_filter($items, static function ($item) {
            return !in_array($item['command'], ['sh', 'ps', 'head'], true);
        }));
    }
}
