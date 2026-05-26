<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class MtWebProxyController extends Controller
{
    private const DEFAULT_UPSTREAM = 'https://web.metatrader.app';

    public function brokerEntry(Request $request, string $broker)
    {
        $resolved = $this->resolveBroker($broker);

        if ($resolved === null) {
            abort(404);
        }

        $query = $request->query();
        $query['broker'] = $resolved['slug'];

        if ($resolved['server'] !== '') {
            // MetaTrader web builds may use either of these keys depending on build version.
            $query['server'] = $resolved['server'];
            $query['servers'] = $resolved['server'];
            $query['trade_server'] = $resolved['server'];
        }

        return redirect('/terminal?' . http_build_query($query), 302);
    }

    public function proxy(Request $request, ?string $path = null)
    {
        $config = $this->proxyConfig();
        $upstreamBase = $config['base_url'];
        $requestPath = ltrim($request->path(), '/');
        if (str_starts_with($requestPath, 'terminal')) {
            $targetPath = $requestPath;
        } else {
            $targetPath = ltrim((string) ($path ?? ''), '/');
        }
        $targetUrl = $upstreamBase . '/' . $targetPath;

        if ($request->getQueryString()) {
            $targetUrl .= '?' . $request->getQueryString();
        }

        try {
            $upstreamResponse = $this->sendUpstream($request, $targetUrl, $upstreamBase, $this->httpClientOptions($config, $targetUrl));
        } catch (Throwable $e) {
            $this->logProxyFailure($request, $targetUrl, $config, $e, false);

            if ($this->shouldDirectFallback($config)) {
                try {
                    $upstreamResponse = $this->sendUpstream($request, $targetUrl, $upstreamBase, $this->httpClientOptions($this->withoutProxy($config), $targetUrl));
                } catch (Throwable $fallbackError) {
                    $this->logProxyFailure($request, $targetUrl, $config, $fallbackError, true);

                    return response('MT proxy upstream connection failed.', 502)
                        ->header('Content-Type', 'text/plain; charset=UTF-8');
                }
            } else {
                return response('MT proxy upstream connection failed.', 502)
                    ->header('Content-Type', 'text/plain; charset=UTF-8');
            }
        }
        $contentType = strtolower((string) $upstreamResponse->header('Content-Type', ''));
        $body = $upstreamResponse->body();

        if ($this->shouldRewriteBody($contentType)) {
            $body = $this->rewriteBody($body, $upstreamBase, $config['ws_gateway_url']);
        }

        $response = response($body, $upstreamResponse->status());

        foreach ($upstreamResponse->headers() as $name => $values) {
            $lower = strtolower($name);

            if (in_array($lower, ['content-length', 'content-encoding', 'transfer-encoding', 'connection'], true)) {
                continue;
            }

            foreach ((array) $values as $value) {
                if ($lower === 'location') {
                    $value = $this->rewriteLocation((string) $value, $upstreamBase);
                }

                if ($lower === 'set-cookie') {
                    $value = $this->rewriteSetCookie((string) $value);
                }

                $response->headers->set($name, $value, false);
            }
        }

        return $response;
    }

    private function proxyConfig(): array
    {
        $baseUrl = rtrim((string) config('services.mt_web_proxy.base_url', self::DEFAULT_UPSTREAM), '/');
        $wsGatewayUrl = (string) config('services.mt_web_proxy.ws_gateway_url', '/terminal');
        $noProxy = array_values(array_filter(array_map(
            static fn (string $host): string => trim($host),
            explode(',', (string) config('services.mt_web_proxy.no_proxy', ''))
        )));

        return [
            'base_url' => $baseUrl,
            'ws_gateway_url' => $wsGatewayUrl,
            'timeout' => (float) config('services.mt_web_proxy.timeout', 45),
            'connect_timeout' => (float) config('services.mt_web_proxy.connect_timeout', 15),
            'http_proxy' => (string) config('services.mt_web_proxy.http_proxy', ''),
            'https_proxy' => (string) config('services.mt_web_proxy.https_proxy', ''),
            'no_proxy' => $noProxy,
            'allow_direct_fallback' => (bool) config('services.mt_web_proxy.allow_direct_fallback', true),
        ];
    }

    public function brokerRoutePattern(): string
    {
        $aliases = [];

        foreach ($this->brokerMap() as $slug => $broker) {
            $aliases[] = $slug;

            foreach ((array) ($broker['aliases'] ?? []) as $alias) {
                $normalized = $this->normalizeBrokerKey((string) $alias);
                if ($normalized !== '') {
                    $aliases[] = $normalized;
                }
            }
        }

        $aliases = array_values(array_unique(array_filter($aliases)));
        $escaped = array_map(static fn (string $item): string => preg_quote($item, '/'), $aliases);

        if ($escaped === []) {
            return 'a^';
        }

        return '(?:' . implode('|', $escaped) . ')';
    }

    private function brokerMap(): array
    {
        $brokers = config('services.mt_web_proxy.brokers', []);

        return is_array($brokers) ? $brokers : [];
    }

    private function resolveBroker(string $broker): ?array
    {
        $target = $this->normalizeBrokerKey($broker);

        if ($target === '') {
            return null;
        }

        foreach ($this->brokerMap() as $slug => $config) {
            if ($target === $this->normalizeBrokerKey((string) $slug)) {
                return [
                    'slug' => (string) $slug,
                    'server' => trim((string) ($config['server'] ?? '')),
                ];
            }

            foreach ((array) ($config['aliases'] ?? []) as $alias) {
                if ($target === $this->normalizeBrokerKey((string) $alias)) {
                    return [
                        'slug' => (string) $slug,
                        'server' => trim((string) ($config['server'] ?? '')),
                    ];
                }
            }
        }

        return null;
    }

    private function normalizeBrokerKey(string $value): string
    {
        $value = trim($value);
        $value = str_replace(['ك', 'ي', 'ة'], ['ک', 'ی', 'ه'], $value);
        $value = str_replace('‌', ' ', $value);
        $value = mb_strtolower($value, 'UTF-8');

        return (string) preg_replace('/[\s\-_]+/u', '', $value);
    }

    private function sendUpstream(Request $request, string $targetUrl, string $upstreamBase, array $options)
    {
        $client = Http::withHeaders($this->forwardHeaders($request, $upstreamBase))
            ->withOptions($options);

        if (!in_array($request->method(), ['GET', 'HEAD'], true)) {
            $contentType = (string) $request->header('Content-Type', 'application/octet-stream');
            $client = $client->withBody($request->getContent(), $contentType);
        }

        return $client->send($request->method(), $targetUrl);
    }

    private function withoutProxy(array $config): array
    {
        $config['http_proxy'] = '';
        $config['https_proxy'] = '';
        $config['no_proxy'] = [];

        return $config;
    }

    private function shouldDirectFallback(array $config): bool
    {
        if (!($config['allow_direct_fallback'] ?? true)) {
            return false;
        }

        return ($config['http_proxy'] !== '') || ($config['https_proxy'] !== '');
    }

    private function httpClientOptions(array $config, string $targetUrl): array
    {
        $options = [
            'allow_redirects' => false,
            'http_errors' => false,
            'timeout' => $config['timeout'],
            'connect_timeout' => $config['connect_timeout'],
        ];

        $proxyUrl = $this->effectiveProxyUrl($config, $targetUrl);

        if ($proxyUrl !== '') {
            $options['proxy'] = $this->normalizeProxyUrl($proxyUrl);
        }

        $curlOptions = $this->proxyCurlOptions($proxyUrl);

        if ($curlOptions !== []) {
            $options['curl'] = $curlOptions;
        }

        return $options;
    }

    private function normalizeProxyUrl(string $proxyUrl): string
    {
        $parts = parse_url($proxyUrl);

        if ($parts === false) {
            return $proxyUrl;
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? ''));

        if (!in_array($scheme, ['socks5', 'socks5h'], true)) {
            return $proxyUrl;
        }

        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        return $host . $port;
    }

    private function effectiveProxyUrl(array $config, string $targetUrl): string
    {
        $scheme = strtolower((string) parse_url($targetUrl, PHP_URL_SCHEME));
        $host = strtolower((string) parse_url($targetUrl, PHP_URL_HOST));

        if ($host !== '' && $this->hostMatchesNoProxy($host, $config['no_proxy'])) {
            return '';
        }

        return $scheme === 'http' ? $config['http_proxy'] : $config['https_proxy'];
    }

    private function proxyCurlOptions(string $proxyUrl): array
    {

        if ($proxyUrl === '') {
            return [];
        }

        $parts = parse_url($proxyUrl);

        if ($parts === false) {
            return [];
        }

        $proxyScheme = strtolower((string) ($parts['scheme'] ?? ''));

        if (!in_array($proxyScheme, ['socks5', 'socks5h'], true)) {
            return [];
        }

        $curlOptions = [
            CURLOPT_PROXYTYPE => $proxyScheme === 'socks5h' ? CURLPROXY_SOCKS5_HOSTNAME : CURLPROXY_SOCKS5,
        ];

        if (isset($parts['user']) || isset($parts['pass'])) {
            $curlOptions[CURLOPT_PROXYUSERPWD] = rawurldecode((string) ($parts['user'] ?? '')) . ':' . rawurldecode((string) ($parts['pass'] ?? ''));
        }

        return $curlOptions;
    }

    private function hostMatchesNoProxy(string $host, array $noProxy): bool
    {
        foreach ($noProxy as $rule) {
            $rule = strtolower(trim($rule));

            if ($rule === '') {
                continue;
            }

            if ($rule === '*') {
                return true;
            }

            if ($host === ltrim($rule, '.')) {
                return true;
            }

            if (str_starts_with($rule, '.') && str_ends_with($host, $rule)) {
                return true;
            }
        }

        return false;
    }

    private function logProxyFailure(Request $request, string $targetUrl, array $config, Throwable $error, bool $directFallback): void
    {
        Log::warning('MT proxy upstream request failed.', [
            'method' => $request->method(),
            'request_path' => $request->path(),
            'query' => $request->getQueryString(),
            'target_url' => $targetUrl,
            'using_proxy' => !$directFallback,
            'http_proxy_configured' => $config['http_proxy'] !== '',
            'https_proxy_configured' => $config['https_proxy'] !== '',
            'error' => $error->getMessage(),
        ]);
    }

    private function forwardHeaders(Request $request, string $upstreamBase): array
    {
        $headers = [];
        $blocked = [
            'host',
            'content-length',
            'accept-encoding',
            'connection',
            'x-forwarded-for',
            'x-forwarded-host',
            'x-forwarded-proto',
        ];

        foreach ($request->headers->all() as $name => $values) {
            if (in_array(strtolower($name), $blocked, true)) {
                continue;
            }

            $headers[$name] = implode(',', (array) $values);
        }

        $headers['Accept-Encoding'] = 'identity';
        $headers['Origin'] = $upstreamBase;
        $headers['Referer'] = $upstreamBase . '/';

        return $headers;
    }

    private function shouldRewriteBody(string $contentType): bool
    {
        $types = [
            'text/html',
            'text/css',
            'application/javascript',
            'text/javascript',
            'application/json',
        ];

        foreach ($types as $type) {
            if (str_contains($contentType, $type)) {
                return true;
            }
        }

        return false;
    }

    private function rewriteBody(string $body, string $upstreamBase, string $wsGatewayUrl): string
    {
        $map = [
            $upstreamBase . '/' => '/mt/',
            $upstreamBase => '/mt',
            'https://web.metatrader.app/' => '/mt/',
            'https://web.metatrader.app' => '/mt',
            '//web.metatrader.app/' => '/mt/',
            '//web.metatrader.app' => '/mt',
            '"/terminal/' => '"/mt/terminal/',
            "'/terminal/" => "'/mt/terminal/",
            '=/terminal/' => '=/mt/terminal/',
            '("/terminal/' => '("/mt/terminal/',
            "('/terminal/" => "('/mt/terminal/",
            '\\/terminal\\/' => '\\/mt\\/terminal\\/',
            'href="/' => 'href="/mt/',
            "href='/" => "href='/mt/",
            'src="/' => 'src="/mt/',
            "src='/" => "src='/mt/",
            'action="/' => 'action="/mt/',
            "action='/" => "action='/mt/",
            'content="/' => 'content="/mt/',
            "content='/" => "content='/mt/",
            'url(/' => 'url(/mt/',
            "fetch('/" => "fetch('/mt/",
            'fetch("/' => 'fetch("/mt/',
            '/mt/mt/terminal/' => '/mt/terminal/',
            '/mt/mt/' => '/mt/',
        ];
                $rewritten = str_replace(array_keys($map), array_values($map), $body);

                if (stripos($rewritten, '<html') !== false && stripos($rewritten, '<head') !== false) {
                        $rewritten = preg_replace(
                                '/<head(.*?)>/i',
                                '<head$1>' . $this->wsShimScript($wsGatewayUrl),
                                $rewritten,
                                1
                        ) ?? $rewritten;
                }

                return $rewritten;
    }

        private function wsShimScript(string $wsGatewayUrl): string
        {
                $safeGatewayUrl = str_replace(['\\', "'"], ['\\\\', "\\'"], trim($wsGatewayUrl));

                return <<<HTML
<script>
(function () {
    var native = window.WebSocket;
    if (!native) return;
    var wsGateway = '{$safeGatewayUrl}' || '/terminal';

    function normalizeGateway(url) {
        if (typeof url !== 'string' || url === '') {
            return '/terminal';
        }

        if (/^wss?:\/\//i.test(url)) {
            return url;
        }

        return url.charAt(0) === '/' ? url : '/' + url;
    }

    wsGateway = normalizeGateway(wsGateway);

    function getPathSuffix(path) {
        var cleaned = path || '/';

        if (cleaned.indexOf('/mt/terminal') === 0) {
            cleaned = cleaned.slice('/mt'.length);
        }

        if (cleaned.indexOf('/terminal') !== 0) {
            return cleaned === '/' ? '' : cleaned;
        }

        return cleaned.slice('/terminal'.length);
    }

    function buildGatewayUrl(parsed) {
        var suffix = getPathSuffix(parsed.pathname || '/');

        if (/^wss?:\/\//i.test(wsGateway)) {
            var base = new URL(wsGateway);
            var nextPath = (base.pathname.replace(/\/$/, '') + '/' + suffix.replace(/^\//, '')).replace(/\/+/g, '/');
            if (nextPath === '') nextPath = '/';
            base.pathname = nextPath;
            base.search = parsed.search || '';
            base.hash = parsed.hash || '';
            return base.toString();
        }

        var protocol = location.protocol === 'https:' ? 'wss:' : 'ws:';
        var hostBase = protocol + '//' + location.host;
        var gatewayPath = wsGateway.replace(/\/$/, '');
        var next = (gatewayPath + '/' + suffix.replace(/^\//, '')).replace(/\/+/g, '/');
        if (next === '') next = '/';

        return hostBase + next + (parsed.search || '') + (parsed.hash || '');
    }

    function mapUrl(url) {
        if (typeof url !== 'string') return url;

        try {
            var wsBase = (location.protocol === 'https:' ? 'wss://' : 'ws://') + location.host + '/';
            var parsed = new URL(url, wsBase);

            if (parsed.protocol !== 'ws:' && parsed.protocol !== 'wss:') {
                return url;
            }

            return buildGatewayUrl(parsed);
        } catch (e) {
            return url;
        }
    }

    function PatchedWebSocket(url, protocols) {
        var mapped = mapUrl(url);
        return protocols === undefined ? new native(mapped) : new native(mapped, protocols);
    }

    PatchedWebSocket.prototype = native.prototype;
    Object.setPrototypeOf(PatchedWebSocket, native);

    try {
        Object.getOwnPropertyNames(native).forEach(function (k) {
            if (!(k in PatchedWebSocket)) {
                Object.defineProperty(PatchedWebSocket, k, Object.getOwnPropertyDescriptor(native, k));
            }
        });
    } catch (e) {
        // no-op
    }

    window.WebSocket = PatchedWebSocket;
})();
</script>
HTML;
        }

    private function rewriteLocation(string $location, string $upstreamBase): string
    {
        $normalizedBase = rtrim($upstreamBase, '/');

        if (str_starts_with($location, $normalizedBase)) {
            $suffix = substr($location, strlen($normalizedBase));
            return '/mt' . ($suffix === '' ? '' : $suffix);
        }

        if (str_starts_with($location, '/')) {
            return '/mt' . $location;
        }

        if (preg_match('/^https?:\/\//i', $location)) {
            return $location;
        }

        return '/mt/' . ltrim($location, '/');
    }

    private function rewriteSetCookie(string $cookie): string
    {
        $cookie = preg_replace('/;\s*Domain=[^;]+/i', '', $cookie);

        if (!preg_match('/;\s*Path=/i', $cookie)) {
            $cookie .= '; Path=/mt';
        } else {
            $cookie = preg_replace('/;\s*Path=[^;]*/i', '; Path=/mt', $cookie);
        }

        return $cookie;
    }
}
