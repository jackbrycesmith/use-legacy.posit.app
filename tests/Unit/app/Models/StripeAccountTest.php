<?php

use App\Models\Organisation;
use App\Models\StripeAccount;
use App\Models\User;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use Stripe\Account;

test('it has an organisation', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']);

    $stripeAccount = factory(StripeAccount::class)->create(['owner_id' => $org->id]);

    assertInstanceOf(Organisation::class, $stripeAccount->owner);
    assertEquals($org->id, $stripeAccount->owner->id);
});

test('it cannot fill attributes from stripe account object if account id mismatch', function () {
    $stripeAccount = factory(StripeAccount::class)->create();

    $this->expectException(InvalidArgumentException::class);

    $stripeAccount->fillFrom(new Account);
});

test('it can fill attributes from stripe account object', function () {
    $stripeAccount = factory(StripeAccount::class)->create();

    $stripeAccountFromApi = new Account($stripeAccount->id);
    assertInstanceOf(StripeAccount::class, $stripeAccount->fillFrom($stripeAccountFromApi));
    // TODO test it actually fills attributes correctly?
});

test('it can updateFromStripeApi', function () {
    $stripeAccount = factory(StripeAccount::class)->create();

    Stripe::fake(
        $expected = new \Stripe\Account()
    );

    $stripeAccount->updateFromStripeApi($shouldUpdate = false);

    Stripe::assertInvoked(\Stripe\Account::class, 'retrieve');
});
