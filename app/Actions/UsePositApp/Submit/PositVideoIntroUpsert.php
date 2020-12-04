<?php

namespace App\Actions\UsePositApp\Submit;

use App\Http\Resources\VideoResource;
use App\Jobs\ConvertVideoForDownloading;
use App\Models\Posit;
use App\Models\Video;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Action;

class PositVideoIntroUpsert extends Action
{
    /**
     * Specify routes for this action.
     *
     * @param \Illuminate\Routing\Router $router The router
     *
     * @return void
     */
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth:sanctum', 'verified'])
            ->post('/posit/{posit:uuid}/video-intro', static::class)
            ->where('posit', Constant::PATTERN_UUID)
            ->name('use.posit.video-intro');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Posit $posit)
    {
        return $this->can('update', $posit);
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'uuid' => ['required', 'uuid'],
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle(Posit $posit)
    {
        $uuid = Arr::get($this->validated(), 'uuid');
        $tmpFilename = "tmp/{$uuid}";

        $this->ensureTempFileExists($tmpFilename);

        // TODO validate this file before moving? e.g. video type & e.g. size limits
        // TODO delete any previous video.
        //
        $tmpStorageDisk = $this->getStorageDiskName();

        $video = $posit->video()->create([
            'tmp_path' => $tmpFilename,
            'tmp_size' => Storage::disk($tmpStorageDisk)->size($tmpFilename),
            'tmp_disk' => $tmpStorageDisk,
        ]);

        // TODO dispatch conversion jobs
        ConvertVideoForDownloading::dispatch($video);

        return $video;
    }

    public function response(Video $video)
    {
        return new VideoResource($video);
    }

    /**
     * Determines if temporary file exists.
     *
     * @param string $filename The filename
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ensureTempFileExists(string $filename)
    {
        if (! Storage::disk($this->getStorageDiskName())->exists($filename)) {
            throw ValidationException::withMessages([
                'uuid' => ['Temp file does not exist.'],
            ]);
        }
    }

    /**
     * Gets the storage disk name.
     *
     * @return string The storage disk name.
     */
    protected static function getStorageDiskName(): string
    {
        return config('filesystems.s3_uploads_disk');
    }
}
