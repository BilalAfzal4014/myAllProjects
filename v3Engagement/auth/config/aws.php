<?php
return [
    's3'=>[
        'aws_service_name' => 's3',
        'access_key'  => env('AWS_KEY'),
        'secret_key'  => env('AWS_SECRET'),
        'bucket_name' => env('AWS_BUCKET'),
        'region'      => env('AWS_REGION'),
        'host_name'   => '.s3.eu-west-2.amazonaws.com',
    ]
];