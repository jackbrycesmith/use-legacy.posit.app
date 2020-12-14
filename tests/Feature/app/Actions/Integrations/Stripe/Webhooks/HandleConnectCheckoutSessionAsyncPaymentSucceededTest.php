<?php

use App\Actions\Integrations\Stripe\Webhooks\HandleConnectCheckoutSessionAsyncPaymentSucceeded;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Stripe\Event as StripeApiEvent;
use function Tests\getStub;

it('listens for event', function () {
    $this->partialMock(HandleConnectCheckoutSessionAsyncPaymentSucceeded::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    // TODO get async success e.g. bacs...
    $stripeApiEvent = StripeApiEvent::constructFrom(getStub("stripeConnectCheckoutSessionCompleted"));

    $webhook = new ConnectWebhook($stripeApiEvent);

    event('stripe.connect.webhooks:checkout.session.async_payment_succeeded', $webhook);
})->only();
