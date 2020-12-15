<?php

use App\Models\StripeAccount;
use App\Models\Team;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use Stripe\Account;
use function Pest\Faker\faker;

test('stripe account is owned by a team', function () {
    $team = Team::factory()->create();
    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $team->id]);

    assertInstanceOf(Team::class, $stripeAccount->owner);
    assertEquals($team->id, $stripeAccount->owner->id);
});

test('it cannot fill attributes from stripe account object if account id mismatch', function () {
    $stripeAccount = StripeAccount::factory()->create();

    $this->expectException(InvalidArgumentException::class);

    $stripeAccount->fillFrom(new Account);
});

test('it can fill attributes from stripe account object', function () {
    $stripeAccount = StripeAccount::factory()->create();

    $stripeAccountFromApi = new Account($stripeAccount->id);
    assertInstanceOf(StripeAccount::class, $stripeAccount->fillFrom($stripeAccountFromApi));
    // TODO test it actually fills attributes correctly?
});

test('it can updateFromStripeApi', function () {
    $stripeAccount = StripeAccount::factory()->create();

    Stripe::fake(
        $expected = new \Stripe\Account()
    );

    $stripeAccount->updateFromStripeApi($shouldUpdate = false);

    Stripe::assertInvoked(\Stripe\Account::class, 'retrieve');
});

test('it can createFromStripeApi', function () {
    $team = Team::factory()->create();

    Stripe::fake(
        $expected = new \Stripe\Account(faker()->unique()->lexify('acct_????????????????'))
    );

    $stripeAccount = StripeAccount::createFromStripeApi($team->id);

    Stripe::assertInvoked(\Stripe\Account::class, 'create');

    assertEquals($stripeAccount->id, $team->stripeAccount->id);
});

test('it can makeStripeCheckoutSession', function () {
    $stripeAccount = StripeAccount::factory()->create();

    Stripe::fake(
        $expected = new \Stripe\Checkout\Session()
    );

    $stripeAccount->makeStripeCheckoutSession([]);

    Stripe::assertInvoked(\Stripe\Checkout\Session::class, 'create');
});

test('get valid payment methods for currency', function ($currency, $expectedPaymentMethods) {
    expect(
        StripeAccount::validPaymentMethodsForCurrency($currency)
    )->toMatchArray($expectedPaymentMethods);
})->with([
    ['eur', ['card', 'sepa_debit', 'bancontact', 'eps', 'giropay', 'ideal', 'sofort']],
    ['gbp', ['card', 'bacs_debit']],
    ['usd', ['card']]
]);

test('get checkoutSessionPaymentMethodTypes for currency', function ($currency, $capabilities, $expectedPaymentMethods) {

    $stripeAccount = StripeAccount::factory()->create([
        'capabilities' => $capabilities
    ]);

    expect(
        $stripeAccount->checkoutSessionPaymentMethodTypes($currency)
    )->toMatchArray($expectedPaymentMethods);
})->with([
    [
        'gbp',
        ['card_payments' => 'active'],
        ['card']
    ],
    [
        'gbp',
        ['card_payments' => 'active', 'bacs_debit_payments' => 'active'],
        ['card', 'bacs_debit']
    ],
    [
        'eur',
        ['card_payments' => 'active'],
        ['card']
    ],
    [
        'eur',
        ['card_payments' => 'active', 'sepa_debit_payments' => 'active',],
        ['card', 'sepa_debit']
    ],
]);
