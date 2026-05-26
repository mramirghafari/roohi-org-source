<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LbankUserInfoService
{
    public function fetchByUid(string $uid): array
    {
        $url = 'https://roohi.trade/newbot/test_user_info.php';

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->get($url, [
                    'mch' => $uid,
                ]);
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => 'خطا در اتصال به سرور ال بانک',
            ];
        }

        if (!$response->ok()) {
            return [
                'ok' => false,
                'message' => 'پاسخ نامعتبر از ال بانک',
            ];
        }

        $json = $response->json();

        if (
            !isset($json['success']) ||
            !isset($json['inTeam']) ||
            !isset($json['data'])
        ) {
            return [
                'ok' => false,
                'message' => 'ساختار پاسخ ال بانک نامعتبر است',
            ];
        }

        $data = $json['data'] ?? [];
        $assets = $data['assets'] ?? [];

        return [
            'ok' => true,
            'success' => (bool) $json['success'],
            'inTeam' => (bool) $json['inTeam'],
            'deposit' => (bool) ($data['deposit'] ?? false),
            'trade' => (bool) ($data['trade'] ?? false),
            'kycStatus' => $data['kycStatus'] ?? null,
            'spot' => (float) ($assets['spot'] ?? 0),
            'futures' => (float) ($assets['futures'] ?? 0),
            'total' => (float) ($assets['total'] ?? 0),
            'bonus' => (float) ($assets['bonus'] ?? 0),
            'spotUsdt' => (float) ($assets['spot_usdt'] ?? 0),
            'raw' => $json,
        ];
    }
}
