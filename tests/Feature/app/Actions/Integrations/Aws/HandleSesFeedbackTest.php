<?php

use Illuminate\Support\Facades\Bus;
use function Tests\getStub;

// NOTE: php://input is null for tests, which is needed to validate, but testing job separately.
it('sends ok response', function () {
    $data = getStub('sesBounceWebhookContent');
    $data['SubscribeURL'] = url('test-route');

    Bus::fake();
    $_SERVER['HTTP_X_AMZ_SNS_MESSAGE_TYPE'] = 'SubscriptionConfirmation';
    $response = $this->postJson('/aws-ses-feedback', $data);

    $response->assertStatus(200);
});
