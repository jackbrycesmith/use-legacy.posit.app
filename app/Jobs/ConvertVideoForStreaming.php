<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->video->is_converted_for_streaming) {
            return;
        }

        $lowerBitrateFormat  = (new X264('aac', 'libx264'))->setKiloBitrate(250);
        $lowBitrateFormat  = (new X264('aac', 'libx264'))->setKiloBitrate(500);

        $toPath = "converted_videos/{$this->video->uuid}/hls.m3u8";
        $toDisk = 's3-local';

        FFMpeg::fromDisk($this->video->disk)
            ->open($this->video->path)
            ->exportForHLS()
            ->addFormat($lowerBitrateFormat)
            ->addFormat($lowBitrateFormat)
            ->toDisk($toDisk)
            ->save($toPath);

        // update the database so we know the convertion is done!
        $this->video->update([
            'streamable_at' => now(),
        ]);
    }
}
