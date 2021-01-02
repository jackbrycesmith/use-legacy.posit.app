<?php

use App\Actions\Integrations\Stripe\Webhooks\HandleConnectAccountUpdated;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Stripe\Event as StripeApiEvent;
use function Tests\getStub;

it('listens for event', function () {
    $this->partialMock(HandleConnectAccountUpdated::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    // TODO get the proper payload
    $stub = getStub("stripeConnectCheckoutSessionAsyncPaymentFailed");
    $stripeApiEvent = StripeApiEvent::constructFrom($stub);

    $webhook = new ConnectWebhook($stripeApiEvent);

    event('stripe.connect.webhooks:account.updated', $webhook);
});
