<?php

use App\Actions\Integrations\Stripe\Webhooks\HandleConnectCustomerCreated;
use App\Models\StripeAccount;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Stripe\Event as StripeApiEvent;
use function Tests\getStub;

it('listens for event', function () {
    $this->partialMock(HandleConnectCustomerCreated::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    $stripeApiEvent = StripeApiEvent::constructFrom(getStub("stripeConnectCustomerCreatedWebhook"));

    $webhook = new ConnectWebhook($stripeApiEvent);

    event('stripe.connect.webhooks:customer.created', $webhook);
});

it('creates customer entry in db', function () {
    $stripeApiEvent = StripeApiEvent::constructFrom(getStub("stripeConnectCustomerCreatedWebhook"));

    $stripeAccount = StripeAccount::factory()->create(['id' => $stripeApiEvent->account]);

    $customerId = data_get($stripeApiEvent, 'data.object.id');
    $webhook = new ConnectWebhook($stripeApiEvent, $stripeAccount);

    $this->assertDatabaseMissing('stripe_customers', [
        'id' => $customerId
    ]);

    event('stripe.connect.webhooks:customer.created', $webhook);

    $this->assertDatabaseHas('stripe_customers', [
        'id' => $customerId
    ]);

});
