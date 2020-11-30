<?php

use App\Actions\Webhooks\HandleSetWebhookCallProcessed;
use App\Events\WebhookCallProcessedEvent;
use Spatie\WebhookClient\Models\WebhookCall;

it('listens for event', function () {
    $this->partialMock(HandleSetWebhookCallProcessed::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    event(new WebhookCallProcessedEvent(new WebhookCall()));
});
