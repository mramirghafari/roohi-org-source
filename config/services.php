<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'lbank' => [
        'api_key' => env('LBANK_API_KEY'),
        'api_secret' => env('LBANK_API_SECRET'),
    ],

    'mt_web_proxy' => [
        'base_url' => env('MT_WEB_PROXY_BASE_URL', 'https://web.metatrader.app'),
        'ws_gateway_url' => env('MT_WEB_PROXY_WS_GATEWAY_URL', '/terminal'),
        'timeout' => (float) env('MT_WEB_PROXY_TIMEOUT', 45),
        'connect_timeout' => (float) env('MT_WEB_PROXY_CONNECT_TIMEOUT', 15),
        'http_proxy' => env('MT_WEB_PROXY_HTTP_PROXY', ''),
        'https_proxy' => env('MT_WEB_PROXY_HTTPS_PROXY', ''),
        'no_proxy' => env('MT_WEB_PROXY_NO_PROXY', ''),
        'allow_direct_fallback' => env('MT_WEB_PROXY_ALLOW_DIRECT_FALLBACK', true),
        'brokers' => [
            'alpari' => [
                'label' => 'Alpari',
                'server' => env('MT_BROKER_ALPARI_SERVER', 'Alpari'),
                'aliases' => ['alpari', 'آلپاری', 'الپاری'],
            ],
            'litefinance' => [
                'label' => 'LiteFinance',
                'server' => env('MT_BROKER_LITEFINANCE_SERVER', 'LiteFinance'),
                'aliases' => ['litefinance', 'lite-finance', 'لایت فایننس', 'لایتفایننس'],
            ],
            'opofinance' => [
                'label' => 'OpoFinance',
                'server' => env('MT_BROKER_OPOFINANCE_SERVER', 'OpoFinance'),
                'aliases' => ['opofinance', 'opo-finance', 'اوپوفایننس', 'اوپو فایننس'],
            ],
            'startrader' => [
                'label' => 'Startrader',
                'server' => env('MT_BROKER_STARTRADER_SERVER', 'Startrader'),
                'aliases' => ['startrader', 'startrade', 'strartrade', 'star-trade', 'استارتریدر', 'استارترید'],
            ],
            'windsor' => [
                'label' => 'Windsor',
                'server' => env('MT_BROKER_WINDSOR_SERVER', 'Windsor'),
                'aliases' => ['windsor', 'ویندزور', 'وینزور'],
            ],
            'capitalxtend' => [
                'label' => 'CapitalXtend',
                'server' => env('MT_BROKER_CAPITALXTEND_SERVER', 'CapitalXtend'),
                'aliases' => ['capitalxtend', 'capitalspxt', 'capitalspacext', 'capitalspace', 'capital-xtend', 'کاپیتال اکستند', 'کاپیتال اسپیس', 'کپیتال اسپیس'],
            ],
            'tickmill' => [
                'label' => 'Tickmill',
                'server' => env('MT_BROKER_TICKMILL_SERVER', 'Tickmill'),
                'aliases' => ['tickmill', 'tickmillreview', 'tickmill-review', 'تیک میل', 'تیکمیل'],
            ],
            'arongroups' => [
                'label' => 'Aron Groups',
                'server' => env('MT_BROKER_ARONGROUPS_SERVER', 'AronGroups'),
                'aliases' => ['arongroups', 'aron-groups', 'آرون گروپس', 'ارون گروپس'],
            ],
            'smtgroups' => [
                'label' => 'SMT Groups',
                'server' => env('MT_BROKER_SMTGROUPS_SERVER', 'SMTGroups'),
                'aliases' => ['smtgroups', 'smt-groups', 'اس ام تی گروپس', 'اس‌ام‌تی گروپس'],
            ],
        ],
    ],

];
