<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Support\File;

class Video extends Model
{
    use HasUuid;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'downloadable_at' => 'datetime',
        'streamable_at' => 'datetime',
    ];

    /**
     * Get the owning model.
     *
     * @return MorphTo The morph to relationship.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Gets the human readable size attribute.
     *
     * @return string The human readable size attribute.
     */
    public function getHumanReadableSizeAttribute(): string
    {
        return File::getHumanReadableSize($this->size);
    }

    /**
     * Gets the is converted for download attribute.
     *
     * @return boolean The is converted for download attribute.
     */
    public function getIsConvertedForDownloadAttribute(): bool
    {
        return !is_null($this->downloadable_at);
    }

    /**
     * Gets the is converted for streaming attribute.
     *
     * @return boolean The is converted for streaming attribute.
     */
    public function getIsConvertedForStreamingAttribute(): bool
    {
        return !is_null($this->streamable_at);
    }

    public function getUrlAttribute(): ?string
    {
        if (! $this->is_converted_for_download) {
            return null;
        }

        return Storage::disk('s3')->temporaryUrl($this->path, now()->addDays(1));
    }

    public function getHlsUrlAttribute(): ?string
    {
        if (! $this->is_converted_for_streaming) {
            return null;
        }

        return null;

        // return Storage::disk('s3')->url($this-> . '.m3u8')
    }



}
