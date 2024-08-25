<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => $s3 = [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET', env('BUCKET_NAME')),
            'endpoint' => env('AWS_ENDPOINT_URL_S3'),
            'use_path_style_endpoint' => true
        ],

        's3-public' => array_merge($s3, [
            'visibility' => 'public',
            'bucket' => env('S3_PUBLIC_BUCKET'),
        ]),

        's3-video' => array_merge($s3, [
            'bucket' => env('S3_VIDEO_BUCKET'),
        ]),

        's3-uploads' => [
            'driver' => 's3',
            'key' => env('S3_UPLOADS_ACCESS_KEY_ID'),
            'secret' => env('S3_UPLOADS_SECRET_ACCESS_KEY'),
            'region' => env('S3_UPLOADS_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('S3_UPLOADS_BUCKET'),
            'endpoint' => env('S3_UPLOADS_URL'),
            'use_path_style_endpoint' => env('S3_UPLOADS_USE_PATH_STYLE_ENDPOINT', false)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

    /*
    |--------------------------------------------------------------------------
    | S3 Upload Disk (custom)
    |--------------------------------------------------------------------------
    |
    | The filesystem S3 disk that is used for user uploads,
    | for signed URL uploads directly in the browser.
    |
    */

    's3_uploads_disk' => env('S3_UPLOADS_DISK', 's3-uploads'),


    /*
    |--------------------------------------------------------------------------
    | S3 Converted Videos Disk (custom)
    |--------------------------------------------------------------------------
    |
    | The filesystem S3 disk that is used for placing converted videos.
    |
    */

    's3_converted_video_disk' => env('S3_CONVERTED_VIDEO_DISK', 's3-video'),

];
