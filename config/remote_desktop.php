<?php

return [
    'service' => env('REMOTE_DESKTOP_SERVICE', 'mt_terminal'),
    'image' => env('REMOTE_DESKTOP_IMAGE', 'roohitrade/mt-terminal:stable-current'),
    'container_port' => (int) env('REMOTE_DESKTOP_CONTAINER_PORT', 3000),
    'public_scheme' => env('REMOTE_DESKTOP_PUBLIC_SCHEME', 'http'),
    'public_host' => env('REMOTE_DESKTOP_PUBLIC_HOST', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST) ?: 'localhost'),
    'launch_mode' => env('REMOTE_DESKTOP_LAUNCH_MODE', 'direct_port'),
    'launch_page' => ltrim((string) env('REMOTE_DESKTOP_LAUNCH_PAGE', ''), '/'),
    'port_range_start' => (int) env('REMOTE_DESKTOP_PORT_START', 62000),
    'port_range_end' => (int) env('REMOTE_DESKTOP_PORT_END', 62999),
    'volume_root' => env('REMOTE_DESKTOP_VOLUME_ROOT', '/opt/mt-users'),
    'template_volume' => env('REMOTE_DESKTOP_TEMPLATE_VOLUME', '/opt/mt-template-config'),
    'default_ttl_minutes' => (int) env('REMOTE_DESKTOP_DEFAULT_TTL', 43200),
    'default_profile' => env('REMOTE_DESKTOP_DEFAULT_PROFILE', 'balanced'),
    'max_running_instances' => (int) env('REMOTE_DESKTOP_MAX_RUNNING', 25),
    'tmpfs_size' => env('REMOTE_DESKTOP_TMPFS_SIZE', '128m'),
    'docker_log_max_size' => env('REMOTE_DESKTOP_LOG_MAX_SIZE', '10m'),
    'docker_log_max_file' => (int) env('REMOTE_DESKTOP_LOG_MAX_FILE', 3),
    'title' => env('REMOTE_DESKTOP_TITLE', 'RoohiTrade MT Terminal'),
    'timezone' => env('REMOTE_DESKTOP_TZ', 'Asia/Tehran'),
    'password_fallback' => env('REMOTE_DESKTOP_PASSWORD_FALLBACK', 'mobile'),
    'tls' => [
        'cert_path' => env('REMOTE_DESKTOP_TLS_CERT_PATH', '/etc/nginx/ssl/roohitrade.crt'),
        'key_path' => env('REMOTE_DESKTOP_TLS_KEY_PATH', '/etc/nginx/ssl/roohitrade.key'),
    ],
    'container_basic_auth' => env('REMOTE_DESKTOP_CONTAINER_BASIC_AUTH', false),
    'display' => [
        'width' => (int) env('REMOTE_DESKTOP_DISPLAY_WIDTH', 1366),
        'height' => (int) env('REMOTE_DESKTOP_DISPLAY_HEIGHT', 768),
    ],
    'stream' => [
        // Keep stream stable on weaker links/browsers by capping fps and bitrate.
        'framerate' => (string) env('REMOTE_DESKTOP_STREAM_FRAMERATE', '24'),
        'video_bitrate' => (string) env('REMOTE_DESKTOP_STREAM_VIDEO_BITRATE', '6'),
        'h264_crf' => (string) env('REMOTE_DESKTOP_STREAM_H264_CRF', '27'),
    ],
    'selkies_ui' => [
        'show_sidebar' => env('REMOTE_DESKTOP_UI_SHOW_SIDEBAR', false),
        'show_core_buttons' => env('REMOTE_DESKTOP_UI_SHOW_CORE_BUTTONS', false),
        'show_logo' => env('REMOTE_DESKTOP_UI_SHOW_LOGO', false),
    ],
    'resource_profiles' => [
        'lightweight' => [
            'cpus' => env('REMOTE_DESKTOP_LIGHTWEIGHT_CPUS', '1.0'),
            'memory' => env('REMOTE_DESKTOP_LIGHTWEIGHT_MEMORY', '1024m'),
            'memory_swap' => env('REMOTE_DESKTOP_LIGHTWEIGHT_MEMORY_SWAP', '1400m'),
            'shm_size' => env('REMOTE_DESKTOP_LIGHTWEIGHT_SHM', '256m'),
            'pids_limit' => (int) env('REMOTE_DESKTOP_LIGHTWEIGHT_PIDS', 256),
        ],
        'balanced' => [
            'cpus' => env('REMOTE_DESKTOP_BALANCED_CPUS', '1.5'),
            'memory' => env('REMOTE_DESKTOP_BALANCED_MEMORY', '1800m'),
            'memory_swap' => env('REMOTE_DESKTOP_BALANCED_MEMORY_SWAP', '2400m'),
            'shm_size' => env('REMOTE_DESKTOP_BALANCED_SHM', '384m'),
            'pids_limit' => (int) env('REMOTE_DESKTOP_BALANCED_PIDS', 384),
        ],
        'performance' => [
            'cpus' => env('REMOTE_DESKTOP_PERFORMANCE_CPUS', '2.0'),
            'memory' => env('REMOTE_DESKTOP_PERFORMANCE_MEMORY', '2600m'),
            'memory_swap' => env('REMOTE_DESKTOP_PERFORMANCE_MEMORY_SWAP', '3200m'),
            'shm_size' => env('REMOTE_DESKTOP_PERFORMANCE_SHM', '512m'),
            'pids_limit' => (int) env('REMOTE_DESKTOP_PERFORMANCE_PIDS', 512),
        ],
    ],
];
