<?php

namespace App\Models\Concerns;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Spatie\Image\Manipulations;

trait HasLogo
{
    /**
     * Register any media collections & associated conversions.
     *
     * @see https://spatie.be/docs/laravel-medialibrary/v8/working-with-media-collections/defining-media-collections
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->singleFile()
            ->useDisk($this->logoDisk())
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->fit(Manipulations::FIT_CROP, 200, 200)
                    ->format(Manipulations::FORMAT_PNG);
            });
    }

    /**
     * Update the model logo.
     *
     * @param \Illuminate\Http\UploadedFile $logo The logo
     *
     * @return void
     */
    public function updateLogo(UploadedFile $logo): void
    {
        $this
            ->addMedia($logo)
            ->usingName('logo')
            ->usingFileName("logo.{$logo->clientExtension()}")
            ->toMediaCollection('logo');
    }

    /**
     * Gets the logo url attribute.
     *
     * @return null|string The logo url attribute.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($logo = $this->logo()) {
            $conversion = $logo->hasGeneratedConversion('thumb') ? 'thumb' : '';
            return $logo->getFullUrl($conversion);
        }

        return null;
    }

    /**
     * Get the logo
     *
     * @return Media|null
     */
    public function logo()
    {
        return $this->getFirstMedia('logo');
    }

    /**
     * Delete the model logo.
     *
     * @return void
     */
    public function deleteLogo(): void
    {
        $this->clearMediaCollection('logo');
    }

    /**
     * Get the disk that logos should be stored on.
     *
     * @return string
     */
    protected function logoDisk()
    {
        return 's3-public';
    }
}
