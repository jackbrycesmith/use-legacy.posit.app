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
        telegram_me_now("📪 SES `Complaint` recorded");
    }
}
