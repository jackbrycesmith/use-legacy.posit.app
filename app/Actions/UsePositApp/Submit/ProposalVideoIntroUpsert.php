<?php

namespace App\Actions\UsePositApp\Submit;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use App\Models\Proposal;
use App\Utils\Constant;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Action;

class ProposalVideoIntroUpsert extends Action
{
    public static function routes(Router $router)
    {
        $router->domain(use_posit_domain())
            ->middleware(['web', 'auth'])
            ->post('/proposal/{proposal:uuid}/video-intro', static::class)
            ->where('proposal', Constant::PATTERN_UUID)
            ->name('use.proposal.video-intro');
    }

    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize(Proposal $proposal)
    {
        return $this->can('update', $proposal);
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
    public function handle(Proposal $proposal)
    {
        $uuid = Arr::get($this->validated(), 'uuid');
        $tmpFilename = "tmp/{$uuid}";

        $this->ensureTempFileExists($tmpFilename);

        // TODO validate this file before moving? e.g. video type & e.g. size limits; some validation is handled via media library e.g. max file limits...
        $media = $proposal
           ->addMediaFromDisk($tmpFilename, 's3')
           ->storingConversionsOnDisk('s3')
           ->toMediaCollection(Proposal::INTRO_VIDEO_COLLECTION, 's3');

        return $media;
    }

    public function response(Media $media)
    {
        return new MediaResource($media);
    }

    /**
     * Determines if ensure temporary file exists.
     *
     * @param string $filename The filename
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function ensureTempFileExists(string $filename)
    {
        if (! Storage::disk('s3')->exists($filename)) {
            throw ValidationException::withMessages([
                'uuid' => ['Temp file does not exist.'],
            ]);
        }
    }
}
