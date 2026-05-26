<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SocksTest extends Command
{
    protected $signature = 'socks:test {url=https://web.metatrader.app}';
    protected $description = 'Test the configured MT web proxy/SOCKS connection';

    public function handle()
    {
        $url = (string) $this->argument('url');
        $httpProxy = (string) config('services.mt_web_proxy.http_proxy', '');
        $httpsProxy = (string) config('services.mt_web_proxy.https_proxy', '');

        if ($httpProxy === '' && $httpsProxy === '') {
            $this->error('MT web proxy is not configured in services.mt_web_proxy.');

            return self::FAILURE;
        }

        $this->line('Target: ' . $url);
        $this->line('HTTP proxy: ' . ($httpProxy !== '' ? $httpProxy : '[empty]'));
        $this->line('HTTPS proxy: ' . ($httpsProxy !== '' ? $httpsProxy : '[empty]'));

        try {
            $response = Http::withOptions([
                'proxy' => array_filter([
                    'http' => $httpProxy,
                    'https' => $httpsProxy,
                ]),
                'timeout' => (float) config('services.mt_web_proxy.timeout', 45),
                'connect_timeout' => (float) config('services.mt_web_proxy.connect_timeout', 15),
            ])->get($url);
        } catch (\Throwable $e) {
            $this->error('Proxy request failed: ' . $e->getMessage());

            return self::FAILURE;
        }

        $this->info('Status: ' . $response->status());
        $this->line('Body preview:');
        $this->line(substr($response->body(), 0, 500));

        return $response->successful() ? self::SUCCESS : self::FAILURE;
    }
}
