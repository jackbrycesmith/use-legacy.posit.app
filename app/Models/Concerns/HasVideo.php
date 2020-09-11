<?php

namespace App\Models\Concerns;

use App\Models\Video;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasVideo
{
    public static function bootHasVideo()
    {
        static::deleting(function($entity) {
            // TODO probs dispatch a job to delete the files from s3, then the models...
            // $entity->videos()->get()->each->delete();
        });
    }

    /**
     * Get the video that this model owns.
     *
     * @return MorphOne The morph one relationship.
     */
    public function video(): MorphOne
    {
        return $this->morphOne(Video::class, 'model');
    }

    /**
     * Get the videos that this model owns.
     *
     * @return MorphMany The morph many relationship.
     */
    public function videos(): MorphMany
    {
        return $this->morphMany(Video::class, 'model');
    }
}
