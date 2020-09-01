<?php

return [
    'list' => [        
        'AddItemsToCampaignQueuesCommand' => [
            'name'          =>  'campaign:queues',
            'interval'      =>  'everyMinute',
            'enabled'       =>  env('CAMPAIGN_QUEUES_DISPATCH', false),
            'retry_limit'   =>  1,
            'instances'     =>  1,
        ],
        'DispatchCampaignQueuesCommand' => [
            'name'          =>  'dispatch:campaign:queue',
            'interval'      =>  'everyMinute',
            'enabled'       =>  env('CAMPAIGN_QUEUES_DISPATCH', false),
            'retry_limit'   =>  1,
            'instances'     =>  5,
        ],
        'SegmentsDataCacheCommand' => [
            'name'          =>  'segment:cache',
            'interval'      =>  'hourly',
            'enabled'       =>  true,
            'retry_limit'   =>  1,
            'instances'     =>  1,
        ],        
        'ArchiveCampaignTrackingData' => [
            'name'          =>  'tracking:archive',
            'interval'      =>  'weekly',
            'enabled'       =>  env('ARCHIVE_CAMPAIGN_TRACKING', false),
            'retry_limit'   =>  1,
            'instances'     =>  5,
        ],
        'GenerateNewsfeedCacheCommand' => [
            'name'          =>  'newsfeed:cache',
            'interval'      =>  'hourly',
            'enabled'       =>  false,
            'retry_limit'   =>  1,
            'instances'     =>  5,
        ],
        'FlagEmailsCommand' => [
            'name'          =>  'flag:emails',
            'interval'      =>  'daily',
            'enabled'       =>  false,
            'retry_limit'   =>  1,
            'instances'     =>  5,
        ],
    ],
    'intervals' => [
        'everyMinute',
        'everyFiveMinutes',
        'everyTenMinutes',
        'everyThirtyMinutes',
        'hourly',
        'daily',
        'weekly',
        'monthly',
        'weekdays',
        'sundays',
        'mondays',
        'tuesdays',
        'wednesdays',
        'thursdays',
        'fridays',
        'saturdays'
    ],
];
