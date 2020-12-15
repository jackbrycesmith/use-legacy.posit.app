<?php

use App\Actions\Integrations\Stripe\Webhooks\HandleConnectCheckoutSessionAsyncPaymentSucceeded;
use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\States\PositPayment\Paid;
use App\Models\States\Posit\Published;
use App\Models\StripeAccount;
use App\Models\StripeCheckoutSession;
use App\Models\Team;
use App\Models\TeamContact;
use CloudCreativity\LaravelStripe\Webhooks\ConnectWebhook;
use Stripe\Event as StripeApiEvent;
use function Tests\getStub;

it('listens for event', function () {
    $this->partialMock(HandleConnectCheckoutSessionAsyncPaymentSucceeded::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    // TODO get async success e.g. bacs...
    $stripeApiEvent = StripeApiEvent::constructFrom(getStub("stripeConnectCheckoutSessionAsyncPaymentSucceeded"));

    $webhook = new ConnectWebhook($stripeApiEvent);

    event('stripe.connect.webhooks:checkout.session.async_payment_succeeded', $webhook);
});

it('marks posit payment as paid', function () {
    $stub = getStub("stripeConnectCheckoutSessionAsyncPaymentSucceeded");

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

    $webhook = new ConnectWebhook($stripeApiEvent, $stripeAccount);

    event('stripe.connect.webhooks:checkout.session.async_payment_succeeded', $webhook);

    $this->assertDatabaseHas('posit_payments', [
        'id' => $depositPayment->id,
        'state' => Paid::getMorphClass()
    ]);
});
