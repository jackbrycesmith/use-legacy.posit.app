<?php

use App\Actions\Integrations\Stripe\Webhooks\HandleConnectAccountApplicationDeauthorized;
use App\Models\StripeAccount;
use App\Models\StripeCustomer;
use App\Models\Team;
use App\Notifications\Team\TeamStripeAccountDisconnected;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Stripe\Event as StripeApiEvent;
use function Tests\getStub;

it('listens for event', function () {
    $this->partialMock(HandleConnectAccountApplicationDeauthorized::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    // TODO get the proper payload
    $stub = getStub("stripeConnectCheckoutSessionAsyncPaymentFailed");
    $stripeApiEvent = StripeApiEvent::constructFrom($stub);

    $webhook = new ConnectWebhook($stripeApiEvent);

    event('stripe.connect.webhooks:account.application.deauthorized', $webhook);
});

it('deletes stripe account + associated customers, then notifies team that this happened', function () {
    // TODO get the proper payload
    $stub = getStub("stripeConnectCheckoutSessionAsyncPaymentFailed");

    $team = Team::factory()->create();
    $stripeAccount = StripeAccount::factory()->create(['id' => $stub['account'], 'owner_id' => $team->id]);
    $stripeCustomer = StripeCustomer::create(['id' => 'blah', 'stripe_account_id' => $stripeAccount->id]);

    $stripeApiEvent = StripeApiEvent::constructFrom($stub);

    $webhook = new ConnectWebhook($stripeApiEvent, $stripeAccount);

    Notification::fake();

    event('stripe.connect.webhooks:account.application.deauthorized', $webhook);

    $this->assertSoftDeleted($stripeAccount);
    $this->assertSoftDeleted($stripeCustomer);

    Notification::assertSentTo(
        [$team], TeamStripeAccountDisconnected::class
    );
});
