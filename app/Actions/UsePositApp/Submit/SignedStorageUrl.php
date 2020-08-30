<?php

namespace App\Actions\UsePositApp\Submit;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->ensureEnvironmentVariablesAreAvailable($request);

        $client = $this->storageClient();
        $bucket = $_ENV['AWS_BUCKET'];

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
            'url' => 'https://'.$uri->getHost().$uri->getPath().'?'.$uri->getQuery(),
            'headers' => $this->headers($request, $signedRequest),
        ], 201);
    }


    /**
     * Ensure the required environment variables are available.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws InvalidArgumentException
     */
    protected function ensureEnvironmentVariablesAreAvailable(Request $request)
    {
        $missing = array_diff_key(array_flip(array_filter([
            'AWS_BUCKET',
            // $request->input('bucket') ? null : 'AWS_BUCKET',
            'AWS_DEFAULT_REGION',
            'AWS_ACCESS_KEY_ID',
            'AWS_SECRET_ACCESS_KEY'
        ])), $_ENV);

        if (empty($missing)) {
            return;
        }

        throw new InvalidArgumentException(
            "Unable to issue signed URL. Missing environment variables: ".implode(', ', array_keys($missing))
        );
    }

    /**
     * Get the S3 storage client instance.
     *
     * @return \Aws\S3\S3Client
     */
    protected function storageClient()
    {
        return new S3Client([
            'url' => $_ENV['AWS_URL'],
            'endpoint' => $_ENV['AWS_URL'],
            'region' => $_ENV['AWS_DEFAULT_REGION'],
            'version' => 'latest',
            'signature_version' => 'v4',
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => $_ENV['AWS_ACCESS_KEY_ID'],
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
            ]
        ]);


        // $config = [
        //     'region' => $_ENV['AWS_DEFAULT_REGION'],
        //     'version' => 'latest',
        //     'signature_version' => 'v4',
        // ];

        // if (! isset($_ENV['AWS_LAMBDA_FUNCTION_VERSION'])) {
        //     $config['credentials'] = array_filter([
        //         'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? null,
        //         'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null,
        //         'token' => $_ENV['AWS_SESSION_TOKEN'] ?? null,
        //     ]);

        //     if (array_key_exists('AWS_URL', $_ENV) && ! is_null($_ENV['AWS_URL'])) {
        //         $config['url'] = $_ENV['AWS_URL'];
        //         $config['endpoint'] = $_ENV['AWS_URL'];
        //     }
        // }

        // return S3Client::factory($config);
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
            // TODO add this when using filebase, couldn't get minio to work with it included.
            // 'ACL' => $request->input('visibility') ?: $this->defaultVisibility(),
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
