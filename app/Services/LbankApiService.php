<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class LbankApiService
{
    private string $apiKey;
    private string $apiSecret;
    private string $baseUrl = 'https://affiliate.lbankverify.com';

    public function __construct(string $apiKey, string $apiSecret)
    {
        // در یک پروژه واقعی، این مقادیر از فایل .env یا Config لاراول خوانده می‌شوند
        $this->apiKey = $apiKey; 
        $this->apiSecret = $apiSecret;
    }

    /**
     * منطق تولید امضای LBank (مطابق با کد PHP شما)
     * @param array $params
     * @return string
     */
    private function generateSignature(array $params): string
    {
        // 1. مرتب‌سازی پارامترها بر اساس کلید
        ksort($params);

        // 2. ساخت رشته پارامترها (key1=val1&key2=val2...)
        $paramStr = [];
        foreach ($params as $k => $v) {
            // اطمینان از اینکه مقادیر خالی یا نال در URL قرار نگیرند.
            if (!empty($v) || $v === 0 || $v === '0') {
                 $paramStr[] = $k . '=' . $v;
            }
        }
        $paramStr = implode('&', $paramStr);

        // 3. اعمال MD5 و تبدیل به حروف بزرگ
        $md5Str = strtoupper(md5($paramStr));

        // 4. اعمال HmacSHA256
        return hash_hmac('sha256', $md5Str, $this->apiSecret);
    }
    
    /**
     * دریافت موجودی فعلی (Total Asset) کاربر از وب‌سرویس LBank
     * این تابع همان getLbankBalance مورد نیاز شماست.
     * @param string $openId LBank User ID
     * @return float|null
     * @throws Exception
     */
    public function getLbankBalance(string $openId): ?float
    {
        $endpoint = '/affiliate-api/v2/invite/user/info';

        // 1. ساخت Payload اصلی
        $timestamp = (int)(microtime(true) * 1000);
        $echostr = bin2hex(random_bytes(16));
        
        $payload = [
            'openId'            => $openId,
            'api_key'           => $this->apiKey,
            'timestamp'         => $timestamp,
            'signature_method'  => 'HmacSHA256',
            'echostr'           => $echostr,
        ];
        
        // 2. تولید امضا
        $payload['sign'] = $this->generateSignature($payload);

        // 3. ساخت Header ها
        $headers = [
            'Content-Type'       => 'application/x-www-form-urlencoded',
            'timestamp'          => $timestamp,
            'signature_method'   => 'HmacSHA256',
            'echostr'            => $echostr,
        ];

        // 4. ارسال درخواست GET با پارامترها در Body
        // نکته: کد شما از cURL استفاده کرده و پارامترها را در CURLOPT_POSTFIELDS (که معمولا برای POST است) 
        // در کنار متد GET ارسال کرده بود. در Laravel، ما از متد get() با پارامترها در Query String استفاده می‌کنیم،
        // اما چون API LBank کمی غیر استاندارد است (قرار دادن Payload در Body برای GET)، بهتر است از Client Guzzle 
        // به‌صورت مستقیم استفاده کنیم یا همان ساختار cURL را شبیه‌سازی کنیم. 
        // در اینجا، برای سادگی، از متد post لاراول استفاده می‌کنیم اما به API می‌گوییم متد GET است (با CUSTOMREQUEST)
        // اما چون `Http::withHeaders` با `get` پارامترها را به Query String می‌برد، 
        // از حالت پیش‌فرض برای ارسال پارامترها استفاده می‌کنیم (Query String):
        
        $response = Http::withHeaders($headers)
            ->timeout(15)
            ->get($this->baseUrl . $endpoint, $payload);
            
        // 5. بررسی پاسخ
        if ($response->failed()) {
            throw new Exception("LBank API Call Failed. Status: " . $response->status());
        }

        $result = $response->json();
        
        // 6. بررسی خطای داخلی API (result !== 'true')
        if (($result['result'] ?? '') !== 'true' || ($result['error_code'] ?? null)) {
            $errorCode = $result['error_code'] ?? 'unknown';
            $message = $result['msg'] ?? 'API error';
            throw new Exception("LBank API Error [Code: {$errorCode}]: {$message}");
        }
        
        // 7. استخراج موجودی
        $data = $result['data'] ?? [];
        
        // اگر کاربر در تیم Affiliate نبود، موجودی را 0 فرض می‌کنیم.
        if (!($data['inviteResult'] ?? false)) {
             return 0.0;
        }

        // فیلدهای 'currencyTotalFeeAmtUsdt' یا 'currencyTotalFeeAmt' برای موجودی SPOT
        // فیلد 'total' که در خروجی شما محاسبه شده بود، در اینجا محاسبه می‌شود:
        $spot    = (float)($data['currencyTotalFeeAmt'] ?? 0);
        $futures = (float)($data['contractTotalFeeAmt'] ?? 0);

        return $spot + $futures;
    }
}
