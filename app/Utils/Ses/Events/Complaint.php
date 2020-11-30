<?php

namespace App\Utils\Ses\Events;

class Complaint extends SesEvent
{
    public function canHandlePayload(): bool
    {
        return $this->payload['eventType'] === 'Complaint';
    }

    public function handle()
    {
        // TODO event to notify me e.g. via telegram that this happened (should be rare)
    }
}
