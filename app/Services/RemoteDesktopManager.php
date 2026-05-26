<?php

namespace App\Services;

use App\Models\RemoteAccessToken;
use App\Models\RemoteInstance;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

class RemoteDesktopManager
{
    public function startOrReuse(RemoteAccessToken $grant): RemoteInstance
    {
        $existing = RemoteInstance::query()
            ->where('remote_access_token_id', $grant->id)
            ->where('expires_at', '>', now())
            ->where('status', 'running')
            ->latest('id')
            ->first();

        if ($existing) {
            if (!$this->isContainerRunning((string) $existing->container_name)) {
                $existing->forceFill([
                    'status' => 'terminated',
                    'last_seen_at' => now(),
                    'expires_at' => now(),
                ])->save();
            } else {
                $this->ensureDesktopReady((string) $existing->container_name);

                $expectedLaunchUrl = $this->buildLaunchUrl((int) $existing->external_port);
                $existing->forceFill([
                    'last_seen_at' => now(),
                    'access_url' => $expectedLaunchUrl,
                ])->save();

                return $existing;
            }
        }

        $this->guardCapacity();

        $instance = bin2hex(random_bytes(16));
        $externalPort = $this->allocatePort();
        $containerPort = (int) config('remote_desktop.container_port', 3000);
        $containerName = sprintf('mt_user_%d_%s', $grant->user_id, substr($instance, 0, 12));
        $volumePath = rtrim((string) config('remote_desktop.volume_root', '/opt/mt-users'), '/') . '/' . $grant->user_id;
        $this->prepareVolume($volumePath);

        $password = (string) $grant->password;
        $username = (string) ($grant->username ?: ('user' . $grant->user_id));
        $profileName = (string) data_get($grant->meta, 'desktop_profile', config('remote_desktop.default_profile', 'balanced'));
        $profile = $this->resolveProfile($profileName, (array) data_get($grant->meta, 'resource_overrides', []));
        $tlsCertPath = (string) config('remote_desktop.tls.cert_path', '');
        $tlsKeyPath = (string) config('remote_desktop.tls.key_path', '');
        $containerBasicAuth = filter_var(
            config('remote_desktop.container_basic_auth', false),
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        );
        $containerBasicAuth = $containerBasicAuth ?? false;

        $command = [
            'docker',
            'run',
            '-d',
            '--name',
            $containerName,
            '--restart',
            'unless-stopped',
            '--cpus',
            (string) $profile['cpus'],
            '-e',
            'SELKIES_UI_SHOW_SIDEBAR=false',
            '-e',
            'SELKIES_UI_SHOW_CORE_BUTTONS=false',
            '-e',
            'SELKIES_UI_SHOW_LOGO=false',
            '-e',
            'SELKIES_ENABLE_SHARING=false',
            '-e',
            'SELKIES_FILE_TRANSFERS=',
            '--memory',
            (string) $profile['memory'],
            '--memory-swap',
            (string) $profile['memory_swap'],
            '--pids-limit',
            (string) $profile['pids_limit'],
            '--shm-size=' . (string) $profile['shm_size'],
            '--tmpfs',
            '/tmp:size=' . (string) config('remote_desktop.tmpfs_size', '128m'),
            '--log-opt',
            'max-size=' . (string) config('remote_desktop.docker_log_max_size', '10m'),
            '--log-opt',
            'max-file=' . (string) config('remote_desktop.docker_log_max_file', 3),
            '-p',
            $externalPort . ':' . $containerPort,
            '-e',
            'TZ=' . config('remote_desktop.timezone', 'Asia/Tehran'),
            '-e',
            'DISPLAY=:1',
            '-e',
            'CUSTOM_RES_W=' . (int) config('remote_desktop.display.width', 1366),
            '-e',
            'CUSTOM_RES_H=' . (int) config('remote_desktop.display.height', 768),
            '-e',
            'SELKIES_MANUAL_WIDTH=' . (int) config('remote_desktop.display.width', 1366),
            '-e',
            'SELKIES_MANUAL_HEIGHT=' . (int) config('remote_desktop.display.height', 768),
            '-e',
            'MAX_RES=' . (int) config('remote_desktop.display.width', 1366) . 'x' . (int) config('remote_desktop.display.height', 768),
            '-e',
            'SELKIES_FRAMERATE=' . (string) config('remote_desktop.stream.framerate', '30'),
            '-e',
            'SELKIES_VIDEO_BITRATE=' . (string) config('remote_desktop.stream.video_bitrate', '6'),
            '-e',
            'SELKIES_H264_CRF=' . (string) config('remote_desktop.stream.h264_crf', '28'),
            '-e',
            'SELKIES_UI_SHOW_SIDEBAR=' . ((bool) config('remote_desktop.selkies_ui.show_sidebar', false) ? 'true' : 'false'),
            '-e',
            'SELKIES_UI_SHOW_CORE_BUTTONS=' . ((bool) config('remote_desktop.selkies_ui.show_core_buttons', false) ? 'true' : 'false'),
            '-e',
            'SELKIES_UI_SHOW_LOGO=' . ((bool) config('remote_desktop.selkies_ui.show_logo', false) ? 'true' : 'false'),
            '-e',
            'CUSTOM_USER=' . $username,
            '-e',
            'REMOTE_ACCESS_TOKEN=' . (string) $grant->token,
            '-e',
            'TITLE=' . config('remote_desktop.title', 'RoohiTrade MT Terminal'),
            '-v',
            $volumePath . ':/config',
            (string) config('remote_desktop.image', 'lscr.io/linuxserver/webtop:latest'),
        ];

        if ($containerBasicAuth) {
            $command[] = '-e';
            $command[] = 'PASSWORD=' . $password;
        }

        if ($tlsCertPath !== '' && is_file($tlsCertPath)) {
            $command[] = '-v';
            $command[] = $tlsCertPath . ':/config/ssl/cert.pem:ro';
        }

        if ($tlsKeyPath !== '' && is_file($tlsKeyPath)) {
            $command[] = '-v';
            $command[] = $tlsKeyPath . ':/config/ssl/cert.key:ro';
        }

        $process = new Process($command);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(trim($process->getErrorOutput()) ?: 'Failed to start remote desktop container.');
        }

        $this->ensureDesktopReady($containerName);

        $launchUrl = $this->buildLaunchUrl($externalPort);

        return RemoteInstance::query()->create([
            'remote_access_token_id' => $grant->id,
            'instance' => $instance,
            'container_name' => $containerName,
            'internal_port' => $containerPort,
            'external_port' => $externalPort,
            'access_url' => $launchUrl,
            'status' => 'running',
            'last_seen_at' => now(),
            'expires_at' => $grant->expires_at,
        ]);
    }

    public function touchInstance(RemoteInstance $instance): void
    {
        $instance->forceFill(['last_seen_at' => now()])->save();
    }

    public function isContainerRunning(string $containerName): bool
    {
        $containerName = trim($containerName);
        if ($containerName === '') {
            return false;
        }

        $process = new Process([
            'docker',
            'ps',
            '--filter',
            'name=^/' . $containerName . '$',
            '--format',
            '{{.Names}}',
        ]);
        $process->setTimeout(15);
        $process->run();

        if (!$process->isSuccessful()) {
            return false;
        }

        return trim($process->getOutput()) === $containerName;
    }

    public function stopAllForToken(RemoteAccessToken $grant): void
    {
        $instances = RemoteInstance::query()
            ->where('remote_access_token_id', $grant->id)
            ->whereIn('status', ['running', 'starting'])
            ->get();

        foreach ($instances as $instance) {
            $this->terminateInstance($instance);
        }
    }

    public function terminateInstance(RemoteInstance $instance): void
    {
        (new Process(['docker', 'rm', '-f', $instance->container_name]))->run();

        $instance->forceFill([
            'status' => 'terminated',
            'expires_at' => now(),
            'last_seen_at' => now(),
        ])->save();
    }

    private function allocatePort(): int
    {
        $start = (int) config('remote_desktop.port_range_start', 62000);
        $end = (int) config('remote_desktop.port_range_end', 62999);

        for ($port = $start; $port <= $end; $port++) {
            $inDb = RemoteInstance::query()
                ->where('external_port', $port)
                ->where('status', 'running')
                ->where('expires_at', '>', now())
                ->exists();

            if ($inDb) {
                continue;
            }

            $socket = @stream_socket_server('tcp://0.0.0.0:' . $port, $errno, $errstr);
            if ($socket === false) {
                continue;
            }

            fclose($socket);

            return $port;
        }

        throw new \RuntimeException('No free remote desktop port available in configured range.');
    }

    private function guardCapacity(): void
    {
        $maxRunning = (int) config('remote_desktop.max_running_instances', 25);
        if ($maxRunning <= 0) {
            return;
        }

        $running = RemoteInstance::query()
            ->whereIn('status', ['running', 'starting'])
            ->where('expires_at', '>', now())
            ->count();

        if ($running >= $maxRunning) {
            throw new \RuntimeException('Remote desktop capacity is full. Please try again in a few minutes.');
        }
    }

    private function prepareVolume(string $volumePath): void
    {
        @mkdir($volumePath, 0700, true);

        $templatePath = rtrim((string) config('remote_desktop.template_volume', ''), '/');
        if ($templatePath === '' || !is_dir($templatePath)) {
            return;
        }

        $hasContent = $this->directoryHasContent($volumePath);
        $isReady = is_file($volumePath . '/.desktop-ready') && is_file($volumePath . '/.mt-provisioned');

        if ($hasContent && $isReady) {
            return;
        }

        $deleteFlag = $hasContent ? '' : '--delete';
        $command = sprintf(
            'set -e; mkdir -p %s; rsync -rlptD %s %s %s %s',
            escapeshellarg($volumePath),
            $deleteFlag,
            $this->buildTemplateExcludeArgs(),
            escapeshellarg($templatePath . '/'),
            escapeshellarg(rtrim($volumePath, '/') . '/')
        );

        $process = new Process(['bash', '-lc', $command]);
        $process->setTimeout(120);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(trim($process->getErrorOutput()) ?: 'Failed to seed remote desktop volume.');
        }
    }

    private function directoryHasContent(string $path): bool
    {
        if (!is_dir($path)) {
            return false;
        }

        $iterator = new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);

        return $iterator->valid();
    }

    private function buildTemplateExcludeArgs(): string
    {
        $excludes = [
            '.cache',
            '.dbus',
            '.XDG',
            '.mt-logs',
            'ssl',
            '.vnc',
            '.ICEauthority',
            '.Xauthority',
            '.wine/wineserver',
        ];

        return implode(' ', array_map(
            static fn (string $path): string => '--exclude=' . escapeshellarg($path),
            $excludes
        ));
    }

    private function ensureDesktopReady(string $containerName): void
    {
        $containerName = trim($containerName);
        if ($containerName === '') {
            return;
        }

        $script = <<<'BASH'
set -e

mkdir -p /config/.config/openbox
mkdir -p /config/.mt-logs

if ! command -v pcmanfm >/dev/null 2>&1; then
cat >/tmp/roohi-install-pcmanfm.sh <<'EOF'
#!/usr/bin/env bash
set -e
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq || exit 0
apt-get install -y --no-install-recommends pcmanfm >/config/.mt-logs/pcmanfm-install.log 2>&1 || exit 0
su -s /bin/bash abc -c 'DISPLAY=:1 pkill -x pcmanfm >/dev/null 2>&1 || true; DISPLAY=:1 pcmanfm --desktop --profile LXDE >/config/.mt-logs/pcmanfm-live.log 2>&1 &' || true
EOF
chmod 0755 /tmp/roohi-install-pcmanfm.sh || true
nohup /tmp/roohi-install-pcmanfm.sh >/dev/null 2>&1 &
fi

for f in /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default; do
    [[ -f "$f" ]] || continue
    sed -E -i 's/^([[:space:]]*)auth_basic([[:space:]]+.*)$/\1# auth_basic\2/' "$f" || true
    sed -E -i 's/^([[:space:]]*)auth_basic_user_file([[:space:]]+.*)$/\1# auth_basic_user_file\2/' "$f" || true
done
nginx -s reload >/dev/null 2>&1 || true

if [[ ! -x /usr/local/bin/ensure-mt-installed ]]; then
cat >/usr/local/bin/ensure-mt-installed <<'EOF'
#!/usr/bin/env bash
set -euo pipefail

export HOME=/config
export WINEPREFIX=/config/.wine
export DISPLAY=${DISPLAY:-:1}
export WINEDEBUG=-all

LOG_DIR=/config/.mt-logs
INSTALLERS_DIR=/opt/mt-installers
MARKER=/config/.mt-provisioned

mkdir -p "$LOG_DIR"

has_mt4() {
    find "$WINEPREFIX/drive_c" -maxdepth 7 -type f \( -iname terminal.exe -o -iname terminal64.exe \) 2>/dev/null | grep -Eiq 'metatrader[^/]*4|/mt4'
}

has_mt5() {
    find "$WINEPREFIX/drive_c" -maxdepth 7 -type f \( -iname terminal.exe -o -iname terminal64.exe \) 2>/dev/null | grep -Eiq 'metatrader[^/]*5|/mt5'
}

WINE_BIN=""
if command -v wine >/dev/null 2>&1; then
    WINE_BIN="$(command -v wine)"
elif command -v wine64 >/dev/null 2>&1; then
    WINE_BIN="$(command -v wine64)"
elif [[ -x /usr/lib/wine/wine ]]; then
    WINE_BIN="/usr/lib/wine/wine"
elif [[ -x /usr/lib/wine/wine64 ]]; then
    WINE_BIN="/usr/lib/wine/wine64"
fi

if [[ -z "$WINE_BIN" ]]; then
    echo "[mt-deferred] wine runtime not found" >>"$LOG_DIR/provision.log"
    exit 0
fi

if ! has_mt4; then
    mt4_installer="$(find "$INSTALLERS_DIR" -maxdepth 1 -type f \( -iname '*mt4*.exe' -o -iname '*metatrader4*.exe' \) | head -n1 || true)"
    if [[ -n "${mt4_installer:-}" ]]; then
        "$WINE_BIN" "$mt4_installer" /silent /auto >"$LOG_DIR/mt4-install.log" 2>&1 || true
        "$WINE_BIN" "$mt4_installer" /S >>"$LOG_DIR/mt4-install.log" 2>&1 || true
    fi
fi

if ! has_mt5; then
    mt5_installer="$(find "$INSTALLERS_DIR" -maxdepth 1 -type f \( -iname '*mt5*.exe' -o -iname '*metatrader5*.exe' \) | head -n1 || true)"
    if [[ -n "${mt5_installer:-}" ]]; then
        "$WINE_BIN" "$mt5_installer" /silent /auto >"$LOG_DIR/mt5-install.log" 2>&1 || true
        "$WINE_BIN" "$mt5_installer" /S >>"$LOG_DIR/mt5-install.log" 2>&1 || true
    fi
fi

if has_mt4 && has_mt5; then
    touch "$MARKER" 2>/dev/null || true
fi
EOF
chmod 0755 /usr/local/bin/ensure-mt-installed || true
fi

AUTOSTART=/config/.config/openbox/autostart
if [[ ! -f "$AUTOSTART" && -f /etc/xdg/openbox/autostart ]]; then
    cp /etc/xdg/openbox/autostart "$AUTOSTART"
fi

if [[ -f "$AUTOSTART" ]] && ! grep -q 'pcmanfm --desktop --profile LXDE' "$AUTOSTART"; then
cat >>"$AUTOSTART" <<'EOF'

# Show desktop icons when pcmanfm is available.
if command -v pcmanfm >/dev/null 2>&1; then
    pkill -x pcmanfm >/dev/null 2>&1 || true
    pcmanfm --desktop --profile LXDE &
fi
EOF
fi

if [[ -f "$AUTOSTART" ]]; then
    chown abc:abc "$AUTOSTART" 2>/dev/null || true
    chmod 0755 "$AUTOSTART" 2>/dev/null || true
fi

if [[ -x /usr/local/bin/ensure-mt-installed ]]; then
    su -s /bin/bash abc -c 'DISPLAY=:1 /usr/local/bin/ensure-mt-installed >/config/.mt-logs/deferred-install-manual.log 2>&1 &' || true
fi

if command -v pcmanfm >/dev/null 2>&1; then
    su -s /bin/bash abc -c 'DISPLAY=:1 pkill -x pcmanfm >/dev/null 2>&1 || true; DISPLAY=:1 pcmanfm --desktop --profile LXDE >/config/.mt-logs/pcmanfm-live.log 2>&1 &' || true
fi

if [[ -x /usr/local/bin/launch-mt5 ]]; then
    su -s /bin/bash abc -c 'DISPLAY=:1 /usr/local/bin/launch-mt5 >/config/.mt-logs/mt5-manual.log 2>&1 &' || true
fi

BUNDLE=/usr/local/share/kasmvnc/www/dist/main.bundle.js
if [[ -f "$BUNDLE" ]]; then
    sed -i 's#var timeSinceLastActivityInS = (Date.now() - UI.rfb.lastActiveAt) / 1000;#if (UI.rfb == null || typeof UI.rfb.lastActiveAt === "undefined") { return; } var timeSinceLastActivityInS = (Date.now() - UI.rfb.lastActiveAt) / 1000;#g' "$BUNDLE" || true
fi
touch /config/.desktop-ready
BASH;

        $process = new Process([
            'docker',
            'exec',
            $containerName,
            'bash',
            '-lc',
            $script,
        ]);
        $process->setTimeout(20);
        try {
            $process->run();
        } catch (ProcessTimedOutException $e) {
            // Desktop self-heal is best-effort and should not block instance provisioning.
        }
    }

    private function resolveProfile(string $profileName, array $overrides = []): array
    {
        $profiles = (array) config('remote_desktop.resource_profiles', []);
        $fallback = (array) ($profiles['balanced'] ?? [
            'cpus' => '1.5',
            'memory' => '1800m',
            'memory_swap' => '2400m',
            'shm_size' => '384m',
            'pids_limit' => 384,
        ]);

        $selected = (array) ($profiles[$profileName] ?? $fallback);

        $resolved = [
            'cpus' => (string) ($selected['cpus'] ?? $fallback['cpus']),
            'memory' => (string) ($selected['memory'] ?? $fallback['memory']),
            'memory_swap' => (string) ($selected['memory_swap'] ?? $fallback['memory_swap']),
            'shm_size' => (string) ($selected['shm_size'] ?? $fallback['shm_size']),
            'pids_limit' => (int) ($selected['pids_limit'] ?? $fallback['pids_limit']),
        ];

        if (($overrides['cpus'] ?? null) !== null && is_numeric($overrides['cpus'])) {
            $resolved['cpus'] = (string) $overrides['cpus'];
        }
        if (($overrides['memory_mb'] ?? null) !== null && is_numeric($overrides['memory_mb'])) {
            $resolved['memory'] = (int) $overrides['memory_mb'] . 'm';
        }
        if (($overrides['memory_swap_mb'] ?? null) !== null && is_numeric($overrides['memory_swap_mb'])) {
            $resolved['memory_swap'] = (int) $overrides['memory_swap_mb'] . 'm';
        }
        if (($overrides['shm_mb'] ?? null) !== null && is_numeric($overrides['shm_mb'])) {
            $resolved['shm_size'] = (int) $overrides['shm_mb'] . 'm';
        }
        if (($overrides['pids_limit'] ?? null) !== null && is_numeric($overrides['pids_limit'])) {
            $resolved['pids_limit'] = (int) $overrides['pids_limit'];
        }

        return $resolved;
    }

    private function buildLaunchUrl(int $externalPort): string
    {
        $scheme = (string) config('remote_desktop.public_scheme', 'http');
        $host = (string) config('remote_desktop.public_host', 'localhost');
        $launchMode = (string) config('remote_desktop.launch_mode', 'direct_port');
        $launchPage = trim((string) config('remote_desktop.launch_page', ''), '/');

        if ($launchMode === 'proxy_path') {
            if ($launchPage === '') {
                return sprintf('%s://%s/vnc/%d/', $scheme, $host, $externalPort);
            }

            return sprintf('%s://%s/vnc/%d/%s', $scheme, $host, $externalPort, $launchPage);
        }

        if ($launchPage === '') {
            return sprintf('%s://%s:%d', $scheme, $host, $externalPort);
        }

        return sprintf('%s://%s:%d/%s', $scheme, $host, $externalPort, $launchPage);
    }
}
