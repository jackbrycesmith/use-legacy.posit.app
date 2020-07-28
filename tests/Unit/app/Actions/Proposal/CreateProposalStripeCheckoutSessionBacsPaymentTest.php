<?php

use App\Actions\Proposal\CreateProposalStripeCheckoutSessionBacsPayment;
use App\Models\StripeAccount;
use App\Models\User;
use CloudCreativity\LaravelStripe\Facades\Stripe;

it('can CreateProposalStripeCheckoutSessionBacsPayment', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']);
    $stripeAccount = factory(StripeAccount::class)->create([
        'owner_id' => $org->id
    ]);

    $proposal = $org->proposals()->create();

    Stripe::fake(
        $expected = new \Stripe\Checkout\Session('cs_test_xxx')
    );

    CreateProposalStripeCheckoutSessionBacsPayment::run(
        $proposal,
        $amount = 10000
    );

    assertEquals('cs_test_xxx', $proposal->stripeCheckoutSession->id);
    Stripe::assertInvoked(\Stripe\Checkout\Session::class, 'create');
});
