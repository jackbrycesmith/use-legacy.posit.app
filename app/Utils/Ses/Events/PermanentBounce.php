<?php

namespace App\Utils\Ses\Events;

class PermanentBounce extends SesEvent
{
    public function canHandlePayload(): bool
    {
        if ($this->payload['eventType'] !== 'Bounce') {
            return false;
        }

        if ($this->payload['bounce']['bounceType'] !== 'Permanent') {
            return false;
        }

        return true;
    }

    public function handle()
    {
        // TODO event to notify me e.g. via telegram that this happened (should be rare)
    }
}
