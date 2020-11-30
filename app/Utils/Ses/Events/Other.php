<?php

namespace App\Utils\Ses\Events;

class Other extends SesEvent
{
    public function canHandlePayload(): bool
    {
        return true;
    }

    public function handle()
    {

    }
}
