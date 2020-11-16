<?php

namespace App\Actions\Utils;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Action;

class S3GetBucketCors extends Action
{
    protected static $commandSignature = 'utils:s3-get-bucket-cors
                                          {disk : The name of the s3 disk in config/filesystems.php}';

    public function getAttributesFromCommand(Command $command): array
    {
        return [
            'disk' => $command->argument('disk'),
        ];
    }

    public function asCommand(Command $command)
    {
        $this->s3DiskConfig = config("filesystems.disks.{$this->disk}");

        if (Arr::get($this->s3DiskConfig, 'driver') !== 's3') {
            throw new \Exception("Provided disk [{$this->disk}] is not using the s3 driver.");
            return;
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
            $result = $client->getBucketCors([
                'Bucket' => $bucketName, // REQUIRED
            ]);
            var_dump($result);
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }

    public function consoleOutput($result, Command $command)
    {
        // $command->info("Fixed postgres table sequence!");
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
