<?php
return [
    's3'=>[
        'aws_service_name' => 's3',
        'access_key'  => env('S3_KEY'),
        'secret_key'  => env('S3_SECRET'),
        'bucket_name' => env('S3_BUCKET'),
        'region'      => env('S3_REGION'),
        'host_name'   => env('S3_HOST_NAME'),
    ]
];