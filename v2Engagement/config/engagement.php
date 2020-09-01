<?php

return [
    'request_logger' => env('ENABLE_REQUEST_LOGGER', false),
    'jwt_enabled' => env('JWT_ENABLED', false),
    'google_map_key' => env('GOOGLE_MAP_KEY', ''),
    'allowed_upload_limit' => env('ALLOWED_UPLOAD_LIMIT', '80M'),
    'memory_limit' => env('MEMORY_LIMIT', -1),
    'max_execution_time' => env('MAX_EXECUTION_TIME', 600),
    'allowed_radius_location' => env('ALLOWED_RADIUS_LOCATION', 5),
    'max_import_limit' => env('MAX_USER_COUNT_BULK_USER', 50),
    'max_import_limit_size' => env('MAX_REQUEST_SIZE_BULK_USER', 50),
    'language' => 'en',
    'limit' => [
        'segments'          => env('SEGMENTS_LIMIT', 2),
        'queues'            => env('QUEUES_LIMIT', 1),
        'user_attribute'    => env('USER_ATTRIBUTE_LIMIT', 100)
    ],
    'batch' => [       
       'segments' => [
           'interval'  => env('BATCH_SEGMENTS_INTERVAL', 2),
           'increment' => env('BATCH_SEGMENTS_INTERVAL_INCREMENT', 2),
       ],
    ],    
    'urls' => [
        'tracking'  => env('APP_URL').'/campaign/tracking/',        
        'inappview' => env('APP_URL').'/campaign/inapp/view/',
        'unsubscribe' => env('APP_URL').'/email/optout/',
    ],
    'message' => [
        'skip_duplicate' => env('SKIP_DUPLICATE_KEYS', true),
        'verify_email'  => env('VERIFY_EMAIL', false),
        'push' => [
            'mode' => env('PUSH_MODE', 'live'),
            'bg_color' => env('PUSH_BG_COLOR', '#FFFFFF'),
            'adapter'=>env('apns_adapter','ios')
        ],
    ],
    'queue' => [        
        'interval'  => env('QUEUE_JOBS_INTERVAL'),
        'time'      => [
            'interval'  => env('QUEUE_TIME_INTERVAL', 5)
        ]
    ],
    'roles' => [
        'company'   => 'COMPANY',
        'super'     => 'SUPER-ADMIN',
    ],
];