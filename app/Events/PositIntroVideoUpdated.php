<?php

namespace App\Events;

use App\Http\Resources\VideoResource;
use App\Models\Posit;
use App\Models\Video;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PositIntroVideoUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Posit
     */
    public $posit;

    /**
     * @var Video
     */
    public $video;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Posit $posit, Video $video)
    {
        $this->posit = $posit;
        $this->video = $video;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['intro_video' => (new VideoResource($this->video))->toArray(null)];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("posit.{$this->posit->uuid}.intro_video");
    }
}
