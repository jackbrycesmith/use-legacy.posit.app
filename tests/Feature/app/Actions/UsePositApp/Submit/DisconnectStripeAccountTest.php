<?php

use App\Models\StripeAccount;
use App\Models\User;
use CloudCreativity\LaravelStripe\Events\AccountDeauthorized;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use Illuminate\Support\Facades\Event;
use Stripe\OAuth;
use Stripe\StripeObject;
use function Tests\actingAs;

test('disconnect org stripe account requires login', function () {
    $response = $this->put(route('use.submit.disconnect-stripe-account', ['org' => 'blah']));

    $response->assertRedirect(route('login'));
});

test('user cannot disconnect stripe account if not a member of the org', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']);

    $stripeAccount = factory(StripeAccount::class)->create(['owner_id' => $org->id]);

    $otherUser = factory(User::class)->create();

    // Just in case...
    Event::fake();
    Stripe::fake($expected = new StripeObject());

    $response = actingAs($otherUser)->put(route('use.submit.disconnect-stripe-account', ['org' => $org]));

    $response->assertStatus(403);
});


test('user can disconnect stripe account if member of the org', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']);

    $stripeAccount = factory(StripeAccount::class)->create(['owner_id' => $org->id]);

    Event::fake();
    Stripe::fake($expected = new StripeObject());

    $response = actingAs($user)->put(route('use.submit.disconnect-stripe-account', ['org' => $org]));

    $response->assertStatus(204);

    Stripe::assertInvoked(OAuth::class, 'deauthorize', function ($params, $options) use ($stripeAccount) {
        $this->assertSame(['stripe_user_id' => $stripeAccount->id], $params, 'params');
        return true;
    });

    Event::assertDispatched(AccountDeauthorized::class, function ($event) use ($stripeAccount) {
        $this->assertTrue($stripeAccount->is($event->account), 'event account');
        return true;
    });
});
