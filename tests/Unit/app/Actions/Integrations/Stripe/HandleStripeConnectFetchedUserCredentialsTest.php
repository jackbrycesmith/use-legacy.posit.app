<?php

use App\Actions\Integrations\Stripe\UpdateStripeAccountDetails;
use App\Models\Organisation;
use App\Models\StripeAccount;
use CloudCreativity\LaravelStripe\Events\FetchedUserCredentials;
use Illuminate\Support\Facades\Bus;
use Stripe\StripeObject;

test('after fetched user credentials, it dispatches update stripe account details job', function () {
    Bus::fake();

    $stripeAccount = factory(StripeAccount::class)->create();
    $organisation = factory(Organisation::class)->create();
    event(new FetchedUserCredentials($stripeAccount, $organisation, new StripeObject));

    Bus::assertDispatched(UpdateStripeAccountDetails::class);
});
