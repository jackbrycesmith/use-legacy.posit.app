<?php

use App\Jobs\ProcessSesWebhookJob;
use App\Models\SesWebhookCall;
use Aws\Sns\MessageValidator;
use Illuminate\Support\Facades\Event;
use function Tests\getStub;

beforeEach(function () {
    $this->webhookCall = SesWebhookCall::factory()->create([
        'name' => 'ses',
        'external_id' => getStub('sesBounceWebhookContent')['MessageId'],
        'payload' => getStub('sesBounceWebhookContent'),
    ]);
});

it('does nothing and deletes the call if signature is missing', function () {
    $data = getStub('sesBounceWebhookContent');
    $data['Signature'] = null;

    $this->webhookCall->update([
        'payload' => json_encode($data),
    ]);

    Event::fake(WebhookCallProcessedEvent::class);
    $job = new ProcessSesWebhookJob($this->webhookCall);

    $job->handle();

    Event::assertNotDispatched(WebhookCallProcessedEvent::class);
    $this->assertEquals(0, SesWebhookCall::count());
});

it('does nothing if data is missing', function () {
    $data = getStub('sesBounceWebhookContent');
    $data['Message'] = '';

    $this->webhookCall->update([
        'payload' => json_encode($data),
    ]);

    Event::fake(WebhookCallProcessedEvent::class);
    $job = new ProcessSesWebhookJob($this->webhookCall);

    $job->handle();
    Event::assertNotDispatched(WebhookCallProcessedEvent::class);
    $this->assertEquals(0, SesWebhookCall::count());
});

it('does nothing and deletes the call if it is a duplicate ses message id', function () {
    $webhookCallSecond = SesWebhookCall::factory()->create([
        'name' => 'ses',
        'external_id' => getStub('sesBounceWebhookContent')['MessageId'],
        'payload' => getStub('sesBounceWebhookContent'),
    ]);

    Event::fake(WebhookCallProcessedEvent::class);
    (new ProcessSesWebhookJob($this->webhookCall))->handle();
    (new ProcessSesWebhookJob($webhookCallSecond))->handle();

    $this->assertEquals(1, SesWebhookCall::count());
})->skip();

it('fires an event when the webhook is processes', function () {
    $webhookCall = SesWebhookCall::factory()->create([
        'name' => 'ses',
        'external_id' => getStub('sesBounceWebhookContent')['MessageId'],
        'payload' => getStub('sesBounceWebhookContent'),
    ]);

    Event::fake(WebhookCallProcessedEvent::class);

    $this->partialMock(MessageValidator::class, function ($mock) {
        $mock->shouldReceive('isValid')->andReturn(true);
    });

    (new ProcessSesWebhookJob($webhookCall))->handle();

    // TODO this event is supposedly not dispatching
    // its not getting to ProcessSesWebhookJob[28]...
    Event::assertDispatched(WebhookCallProcessedEvent::class);
})->skip();
