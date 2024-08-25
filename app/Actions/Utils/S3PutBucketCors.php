<?php

namespace App\Actions\Utils;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Action;

class S3PutBucketCors extends Action
{
    protected static $commandSignature = 'utils:s3-put-bucket-cors
                                          {disk : The name of the s3 disk in config/filesystems.php}
                                          {--allowedOrigins=* : The AllowedOrigins for CORS}
                                          {--allowedMethods=* : The AllowedMethods for CORS e.g. GET,PUT}
                                          {--allowedHeaders=* : The AllowedHeaders for CORS, e.g. *}
                                          ';

    public function getAttributesFromCommand(Command $command): array
    {
        return [
            'disk' => $command->argument('disk'),
            'allowedOrigins' => $command->option('allowedOrigins'),
            'allowedMethods' => $command->option('allowedMethods'),
            'allowedHeaders' => $command->option('allowedHeaders'),
        ];
    }

    public function asCommand(Command $command)
    {
        $this->s3DiskConfig = config("filesystems.disks.{$this->disk}");

        if (Arr::get($this->s3DiskConfig, 'driver') !== 's3') {
            throw new \Exception("Provided disk [{$this->disk}] is not using the s3 driver.");
            return;
        }

        if (count($this->allowedOrigins) < 1) {
            throw new \Exception("Must pass at least one allowed origin.");
        }

        if (count($this->allowedMethods) < 1) {
            throw new \Exception("Must pass at least one allowed method.");
        }

        if (count($this->allowedHeaders) < 1) {
            throw new \Exception("Must pass at least one allowed header.");
        }
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = $this->s3Client();
        $bucketName = Arr::get($this->s3DiskConfig, 'bucket');

        try {
            $result = $client->putBucketCors([
                'Bucket' => $bucketName,
                'CORSConfiguration' => [
                    'CORSRules' => [
                        [
                            'AllowedHeaders' => $this->allowedHeaders,
                            'AllowedMethods' => $this->allowedMethods,
                            'AllowedOrigins' => $this->allowedOrigins,
                            'ExposeHeaders' => ['ETag', 'x-amz-server-side-encryption', 'x-amz-request-id', 'x-amz-id-2'],
                            'MaxAgeSeconds' => 3000
                        ],
                    ],
                ]
            ]);
            var_dump($result);
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }

    public function consoleOutput($result, Command $command)
    {
        // $command->info("Done!");
    }

    /**
     * Get the s3 client.
     *
     * @return S3Client
     */
    protected function s3Client()
    {
        return new S3Client([
            'url' => Arr::get($this->s3DiskConfig, 'endpoint'),
            'endpoint' => Arr::get($this->s3DiskConfig, 'endpoint'),
            'region' => Arr::get($this->s3DiskConfig, 'region'),
            'version' => 'latest',
            'signature_version' => 'v4',
            'use_path_style_endpoint' => Arr::get($this->s3DiskConfig, 'use_path_style_endpoint'),
            'credentials' => [
                'key' => Arr::get($this->s3DiskConfig, 'key'),
                'secret' => Arr::get($this->s3DiskConfig, 'secret'),
            ]
        ]);
    }
}
