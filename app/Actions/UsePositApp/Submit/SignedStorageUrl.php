<?php

namespace App\Actions\UsePositApp\Submit;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Lorisleiva\Actions\Action;

class SignedStorageUrl extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->post('/upload/signed-storage-url', static::class)
            ->name('use.submit.signed-storage-url');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->can('uploadFiles', $this->user());
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Execute the action and return a result.
     *
     * @param \Illuminate\Http\Request $request The request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        [$client, $bucket] = $this->getS3ClientAndUploadBucket();

        $uuid = (string) Str::uuid();

        $signedRequest = $client->createPresignedRequest(
            $this->createCommand($request, $client, $bucket, $key = ('tmp/'.$uuid)),
            '+5 minutes'
        );

        $uri = $signedRequest->getUri();

        return response()->json([
            'uuid' => $uuid,
            'bucket' => $bucket,
            'key' => $key,
            'url' => $uri->getScheme().'://'.$uri->getHost().$uri->getPath().'?'.$uri->getQuery(),
            'headers' => $this->headers($request, $signedRequest),
        ], 201);
    }


    /**
     * Ensure the required environment variables are available.
     *
     * @param array $s3DiskConfig The S3 disk configuration
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function ensureDiskConfigIsCorrect(array $s3DiskConfig)
    {
        if (Arr::get($s3DiskConfig, 'driver') !== 's3') {
            throw new InvalidArgumentException("Specified disk is not using the s3 driver.");
        }

        $missing = array_diff_key(array_flip(array_filter([
            'endpoint',
            'bucket',
            'region',
            'key',
            'secret',
            'bucket',
        ])), $s3DiskConfig);

        if (empty($missing)) {
            return;
        }

        throw new InvalidArgumentException(
            "Unable to issue signed URL. Missing config values: ".implode(', ', array_keys($missing))
        );
    }

    /**
     * Get the S3 storage client instance, and upload bucket.
     *
     * @return [\Aws\S3\S3Client, string $bucketName]
     *
     * @throws InvalidArgumentException
     */
    protected function getS3ClientAndUploadBucket()
    {
        $uploadDiskName = config('filesystems.s3_uploads_disk');
        $s3DiskConfig = config("filesystems.disks.{$uploadDiskName}", []);
        $this->ensureDiskConfigIsCorrect($s3DiskConfig);

        $s3Client = new S3Client([
            'url' => Arr::get($s3DiskConfig, 'endpoint'),
            'endpoint' => Arr::get($s3DiskConfig, 'endpoint'),
            'region' => Arr::get($s3DiskConfig, 'region'),
            'version' => 'latest',
            'signature_version' => 'v4',
            'use_path_style_endpoint' => Arr::get($s3DiskConfig, 'use_path_style_endpoint'),
            'credentials' => [
                'key' => Arr::get($s3DiskConfig, 'key'),
                'secret' => Arr::get($s3DiskConfig, 'secret'),
            ]
        ]);

        return [$s3Client, $bucket = Arr::get($s3DiskConfig, 'bucket')];
    }

    /**
     * Create a command for the PUT operation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Aws\S3\S3Client  $client
     * @param  string  $bucket
     * @param  string  $key
     * @return \Aws\Command
     */
    protected function createCommand(Request $request, S3Client $client, $bucket, $key)
    {
        return $client->getCommand('putObject', array_filter([
            'Bucket' => $bucket,
            'Key' => $key,
            // TODO couldn't get minio to work with it included (this isn't needed with filebase as uploading to a private bucket).
            'ACL' => $this->defaultVisibility(),
            'ContentType' => $request->input('content_type') ?: 'application/octet-stream',
            'CacheControl' => $request->input('cache_control') ?: null,
            'Expires' => $request->input('expires') ?: null,
        ]));
    }

    /**
     * Get the headers that should be used when making the signed request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \GuzzleHttp\Psr7\Request
     * @return array
     */
    protected function headers(Request $request, $signedRequest)
    {
        return array_merge(
            $signedRequest->getHeaders(),
            [
                'Content-Type' => $request->input('content_type') ?: 'application/octet-stream'
            ]
        );
    }

    /**
     * Get the default visibility for uploads.
     *
     * @return string
     */
    protected function defaultVisibility()
    {
        return 'private';
    }
}
