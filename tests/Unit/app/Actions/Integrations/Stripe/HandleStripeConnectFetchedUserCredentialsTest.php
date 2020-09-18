<?php

use App\Actions\Integrations\Stripe\UpdateStripeAccountDetails;
use App\Models\StripeAccount;
use App\Models\Team;
use CloudCreativity\LaravelStripe\Events\FetchedUserCredentials;
use Illuminate\Support\Facades\Bus;
use Stripe\StripeObject;

test('after fetched user credentials, it dispatches update stripe account details job', function () {
    Bus::fake();

    $stripeAccount = StripeAccount::factory()->create();
    $team = Team::factory()->create();

    event(new FetchedUserCredentials($stripeAccount, $team, new StripeObject));

    Bus::assertDispatched(UpdateStripeAccountDetails::class);
});
