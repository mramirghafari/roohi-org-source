<?php

namespace App\Services;

use App\Models\RemoteSession;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Symfony\Component\Process\Process;

class RemoteBrowserService
{
    public string $domainBase = 'remote.roohitrade.ir';
    public string $image = 'lbank-kiosk:latest';
    public string $network = 'proxy';
    public string $sessionsPath = '/opt/kiosk-sessions';

    // Traefik پشت Nginx است و روی entrypoint web کار می‌کند
    public function createSession(int $userId, int $ttlMinutes, string $targetUrl = 'https://www.lbank.com/'): RemoteSession
    {
        $token = Str::lower(Str::random(12));
        $subdomain = "{$token}.{$this->domainBase}";
        $containerName = "kiosk_{$token}";
        $expiresAt = Carbon::now()->addMinutes($ttlMinutes);

        // مسیر پروفایل (کوکی جدا برای هر سشن)
        $volumePath = "{$this->sessionsPath}/{$token}";
        @mkdir($volumePath, 0700, true);

        $session = RemoteSession::create([
            'user_id' => $userId,
            'token' => $token,
            'subdomain' => $subdomain,
            'container_name' => $containerName,
            'expires_at' => $expiresAt,
            'status' => 'starting',
        ]);

        $labels = [
            'traefik.enable=true',

            // Route: host-based
            "traefik.http.routers.{$containerName}.rule=Host(`{$subdomain}`)",
            "traefik.http.routers.{$containerName}.entrypoints=web",

            // سرویس داخل کانتینر روی 8080
            "traefik.http.services.{$containerName}.loadbalancer.server.port=8080",

            // (مرحله بعدی) ForwardAuth را بعداً اضافه می‌کنیم
        ];

        $cmd = [
            'docker','run','-d',
            '--name', $containerName,
            '--network', $this->network,
            '--restart','unless-stopped',

            // منابع (قابل تنظیم)
            '--memory','768m',
            '--cpus','1.0',
            '--pids-limit','256',

            // امنیت پایه
            '--read-only',
            '--tmpfs','/tmp',
            '--tmpfs','/run',
            '--cap-drop','ALL',
            '--security-opt','no-new-privileges:true',
        ];

        foreach ($labels as $l) {
            $cmd[] = '--label';
            $cmd[] = $l;
        }

        // env و volume
        $cmd = array_merge($cmd, [
            '-e','TZ=Asia/Tehran',
            '-e','RESOLUTION=1280x720',
            '-e',"TARGET_URL={$targetUrl}",
            '-v', "{$volumePath}:/config",
            $this->image
        ]);

        $p = new Process($cmd);
        $p->setTimeout(45);
        $p->run();

        if (!$p->isSuccessful()) {
            $session->update([
                'status' => 'failed',
                'ended_at' => now(),
                'meta' => ['error' => $p->getErrorOutput()],
            ]);
            return $session;
        }

        $session->update([
            'status' => 'active',
            'started_at' => now(),
        ]);

        return $session;
    }

    public function terminateSession(RemoteSession $session): void
    {
        // کانتینر را حذف کن
        (new Process(['docker','rm','-f',$session->container_name]))->run();

        // پروفایل را پاک کن
        $path = "{$this->sessionsPath}/{$session->token}";
        (new Process(['bash','-lc','rm -rf '.escapeshellarg($path)]))->run();

        $session->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);
    }
}
