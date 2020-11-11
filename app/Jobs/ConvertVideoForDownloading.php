<?php

namespace App\Jobs;

use App\Events\ProposalIntroVideoUpdated;
use App\Jobs\ConvertVideoForStreaming;
use App\Models\Proposal;
use App\Models\Video;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertVideoForDownloading implements ShouldQueue
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
        if ($this->video->is_converted_for_download) {
            return;
        }

        // TODO some kind of queue locking...

        // create a video format...
        $lowBitrateFormat = (new X264('aac', 'libx264'))->setKiloBitrate(500);
        $directoryToSaveTo = "converted_videos/{$this->video->uuid}";
        $videoPath = "{$directoryToSaveTo}/intro.mp4";
        $posterPath = "{$directoryToSaveTo}/poster.jpg";
        $toDisk = 's3-private';

        // open the uploaded video from the right disk...
        $uploadedVideo = FFMpeg::fromDisk($this->video->tmp_disk)->open($this->video->tmp_path);

        // Perhaps should do something with this
        // This errors out...

        // add the 'resize' filter...
        // ->addFilter(function ($filters) {
        //     $filters->resize(new Dimension(720, 480));
        // })

        // call the 'export' method...
        $uploadedVideo->export()

        ->onProgress(function ($percentage, $remaining, $rate) {
            logger("{$percentage}% transcoded. {$remaining} seconds left at rate: {$rate}");
        })

        // tell the MediaExporter to which disk and in which format we want to export...
        ->toDisk($toDisk)
        ->inFormat($lowBitrateFormat)

        // call the 'save' method with a filename...
        ->save($videoPath);

        $convertedVideo = FFMpeg::fromDisk($toDisk)->open($videoPath);

        $durationSeconds = $convertedVideo->getDurationInSeconds();

        // Poster create; TODO should probably do this in separate job & ensure size is small
        $uploadedVideo
            ->getFrameFromSeconds(0)
            ->export()
            ->toDisk($toDisk)
            ->save($posterPath);

        // update the database so we know the convertion is done!
        $this->video->update([
            'disk' => $toDisk,
            'path' => $videoPath,
            'poster_path' => $posterPath,
            'poster_disk' => $toDisk,
            'seconds' => $durationSeconds,
            'mime_type' => 'video/mp4',
            'downloadable_at' => now(),
        ]);

        $this->broadcastConvertedVideoToUser();

        ConvertVideoForStreaming::dispatch($this->video);
    }

    /**
     * Broadcasts a converted video to user.
     */
    protected function broadcastConvertedVideoToUser()
    {
        if (is_a($this->video->model, Proposal::class)) {
            event(new ProposalIntroVideoUpdated($this->video->model, $this->video));
        }
    }
}
