<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\RemoteAccessToken;
use App\Models\RemoteInstance;
use App\Services\RemoteDesktopManager;


class RemoteAccessController extends Controller
{
    public function unifiedAccess()
    {
        return view('remote.login', [
            'token' => null,
            'username' => '',
            'actionUrl' => route('remote.access.unified.login'),
        ]);
    }

    public function unifiedLogin(Request $request, RemoteDesktopManager $remoteDesktopManager)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string', 'max:200'],
        ]);

        $grants = RemoteAccessToken::query()
            ->where('service', config('remote_desktop.service', 'mt_terminal'))
            ->where('username', (string) $validated['username'])
            ->valid()
            ->latest('id')
            ->get();

        $grant = null;
        foreach ($grants as $candidate) {
            $expectedPassword = (string) ($candidate->password ?: '');
            if (hash_equals($expectedPassword, (string) $validated['password'])) {
                $grant = $candidate;
                break;
            }
        }

        if (!$grant) {
            return back()->withInput([
                'username' => $validated['username'],
            ])->withErrors([
                'credentials' => 'نام کاربری یا رمز عبور نادرست است.',
            ]);
        }

        return $this->startAuthorizedSession($request, $grant, $remoteDesktopManager);
    }

    public function access(Request $request, string $token)
    {
        $grant = RemoteAccessToken::where('token', $token)
            ->where('service', config('remote_desktop.service', 'mt_terminal'))
            ->valid()
            ->first();

        if (!$grant) {
            abort(403);
        }

        return view('remote.login', [
            'token' => $token,
            'username' => (string) ($grant->username ?: ''),
        ]);
    }

    public function login(Request $request, string $token, RemoteDesktopManager $remoteDesktopManager)
    {
        $grant = RemoteAccessToken::where('token', $token)
            ->where('service', config('remote_desktop.service', 'mt_terminal'))
            ->valid()
            ->first();

        if (!$grant) {
            abort(403);
        }

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:120'],
            'password' => ['required', 'string', 'max:200'],
        ]);

        $expectedUsername = (string) ($grant->username ?: '');
        $expectedPassword = (string) ($grant->password ?: '');

        $usernameOk = hash_equals($expectedUsername, (string) $validated['username']);
        $passwordOk = hash_equals($expectedPassword, (string) $validated['password']);

        if (!$usernameOk || !$passwordOk) {
            return back()->withInput([
                'username' => $validated['username'],
            ])->withErrors([
                'credentials' => 'نام کاربری یا رمز عبور نادرست است.',
            ]);
        }

        return $this->startAuthorizedSession($request, $grant, $remoteDesktopManager);
    }


    public function remoteViewer(Request $req, $instance)
    {
        $remote = RemoteInstance::query()
            ->where('instance', $instance)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $remoteDesktopManager = app(RemoteDesktopManager::class);

        if (!$remoteDesktopManager->isContainerRunning((string) $remote->container_name)) {
            $remote->forceFill([
                'status' => 'terminated',
                'expires_at' => now(),
                'last_seen_at' => now(),
            ])->save();

            abort(410, 'Remote session has ended.');
        }

        $remoteDesktopManager->touchInstance($remote);

        return redirect()->away((string) $remote->access_url);
    }

    public function novncAuthorize()
    {
        $port = (int) request()->header('X-VNC-Port', request()->query('port', 0));
        if ($port < 62000 || $port > 62999) {
            $port = (int) session('remote_access.current_port', 0);
        }
        if ($port < 62000 || $port > 62999) {
            return response('Unauthorized', 401);
        }

        $remote = RemoteInstance::query()
            ->where('external_port', $port)
            ->where('status', 'running')
            ->where('expires_at', '>', now())
            ->latest('id')
            ->first();

        if (!$remote || !$remote->token || !$remote->token->is_enabled) {
            return response('Unauthorized', 401);
        }

        $loginUrl = route('remote.access.unified');
        $authorizedPorts = (array) session('remote_access.allowed_ports', []);
        $sessionEntry = $authorizedPorts[(string) $port] ?? null;

        $isAuthorized = is_array($sessionEntry)
            && ($sessionEntry['instance'] ?? null) === (string) $remote->instance
            && ($sessionEntry['token'] ?? null) === (string) $remote->token->token;

        if (!$isAuthorized) {
            return response('Unauthorized', 401)->header('X-Remote-Access-Url', $loginUrl);
        }

        return response('OK', 200)
            ->header('X-VNC-PORT', (string) $remote->external_port)
            ->header('X-INTERNAL-VNC-PORT', (string) $remote->internal_port);
    }

    private function startAuthorizedSession(Request $request, RemoteAccessToken $grant, RemoteDesktopManager $remoteDesktopManager)
    {
        try {
            $grant->consume();
            $instance = $remoteDesktopManager->startOrReuse($grant);
        } catch (\Throwable $e) {
            report($e);

            return response('Remote desktop is temporarily unavailable. Please retry in a few minutes.', 503);
        }

        $authorizedPorts = (array) $request->session()->get('remote_access.allowed_ports', []);
        $authorizedPorts[(string) $instance->external_port] = [
            'instance' => (string) $instance->instance,
            'token' => (string) $grant->token,
            'authorized_at' => now()->timestamp,
        ];
        $request->session()->put('remote_access.allowed_ports', $authorizedPorts);
        $request->session()->put('remote_access.current_port', (int) $instance->external_port);

        return redirect()->away((string) $instance->access_url);
    }
}
