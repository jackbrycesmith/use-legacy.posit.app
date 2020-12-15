<?php

use App\Actions\Integrations\Stripe\Webhooks\HandleConnectCheckoutSessionAsyncPaymentFailed;
use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\States\PositPayment\Pending;
use App\Models\States\Posit\Published;
use App\Models\StripeAccount;
use App\Models\StripeCheckoutSession;
use App\Models\StripeCustomer;
use App\Models\Team;
use App\Models\TeamContact;
use App\Notifications\Team\TeamPositPaymentFailed;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Illuminate\Support\Facades\Notification;
use Stripe\Event as StripeApiEvent;
use function Tests\getStub;

it('listens for event', function () {
    $this->partialMock(HandleConnectCheckoutSessionAsyncPaymentFailed::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    $stub = getStub("stripeConnectCheckoutSessionAsyncPaymentFailed");

    $stripeApiEvent = StripeApiEvent::constructFrom($stub);

    $webhook = new ConnectWebhook($stripeApiEvent);

    event('stripe.connect.webhooks:checkout.session.async_payment_failed', $webhook);
});

it('notifies team', function () {
    $stub = getStub("stripeConnectCheckoutSessionAsyncPaymentFailed");

    $team = Team::factory()->create();
    $posit = Posit::factory()->create([
        'team_id' => $team->id,
        'state' => Published::class
    ]);
    $depositPayment = PositPayment::factory()->create([
        'type' => PositPayment::TYPE_DEPOSIT,
        'posit_id' => $posit->id,
        'amount' => 1
    ]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $stripeAccount = StripeAccount::factory()->create(['id' => $stub['account'], 'owner_id' => $team->id]);
    $stripeCheckoutSession = StripeCheckoutSession::factory()->make([
        'id' => data_get($stub, 'data.object.id'),
        'stripe_account_id' => $stripeAccount->id
    ]);

    $posit->recipients()->sync([$contact->id]);
    $depositPayment->stripeCheckoutSession()->save($stripeCheckoutSession);
    $posit->refresh();

    $stripeApiEvent = StripeApiEvent::constructFrom($stub);

    // I'm assuming this is created before (in a previous webhook) although this might not hold up
    $customerId = data_get($stripeApiEvent, 'data.object.customer');
    $existingStripeCustomer = StripeCustomer::factory()->create(['id' => $customerId]);

    $webhook = new ConnectWebhook($stripeApiEvent, $stripeAccount);

    Notification::fake();

    event('stripe.connect.webhooks:checkout.session.async_payment_failed', $webhook);

    $this->assertDatabaseHas('posit_payments', [
        'id' => $depositPayment->id,
        'state' => Pending::getMorphClass()
    ]);

    Notification::assertSentTo(
        [$team], TeamPositPaymentFailed::class
    );
});
