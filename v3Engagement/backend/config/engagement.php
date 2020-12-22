<?php

return [
    'api' => [
        'versions' => ['v1'],
        'headers' => [
            'prefix' => 'engagiv-',
            'app' => [
                'app-id',
                'app-name',
                'app-version',
                'app-build',
                'device-type',
                'user-token',
                'lang'
            ],
            'bulkApp'=>[
                'app_id',
                'app_name',
                'app_version',
                'app_build',
                'device_type'
            ],
            'limit'=>100
        ],
        'notifications' => [
            'firebase_server_key'   => env('FIREBASE_API_KEY'),
            'device_types'          => ['android', 'ios', 'web'],
            'platforms'             => ['android', 'ios', 'web', 'universal','email'],
            'notification_types'    => ['email','inapp','push'],
            'inapp_types'           => ['banner', 'dialog','full screen'],
            'inapp_dialogue_types'  => ['top', 'middle', 'bottom'],
            'user_data_type'  => ['conversion','action','user','app','gamification','custom'],
            'params' => [
                'push' => [
                    'title'     => 'title',
                    'message'   => 'body',
                    'target'    => 'link'
                ],
                'inapp' => [
                    'inapp_code',
                    'message',
                    'campaign_code',
                    'campaign_type',
                    'track_key',
                    'view_link',
                    'params',
                    'message_type',
                    'message_position'
                ]
            ],
        ],
        'limit' => [
            'user_token'        => env('USER_TOKEN_LIMIT', 200),
            'device_token_limit'=> env('DEVICE_TOKEN_LIMIT', 1), //** to do */
            'email'             => env('CAMPAIGN_EMAIL_LIMIT', 100),
            'inapp'             => env('CAMPAIGN_INAPP_LIMIT', 100),
            'push'              => env('CAMPAIGN_PUSH_LIMIT', 100),
            'segments'          => env('SEGMENTS_LIMIT', 2),
            'queues'            => env('QUEUES_LIMIT', 100),
            'user_attribute'    => env('USER_ATTRIBUTE_LIMIT', 100)
        ],
        'bulk_import' => [
            'chunk_size' => env('IMPORT_DATA_CHUNK_SIZE', 100)
        ]
    ],
    'url' => [
        'auth' => env('AUTH_WEB_URL'),
        'inappview' => env('CAMPAIGN_INAPP_VIEW_URL'),
    ],
    'limit' => env('RECORDS_PER_PAGE', 20),
    'segments' => [
        'types' => ['user', 'action', 'conversion']
    ]
];