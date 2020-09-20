<?php

use App\Models\StripeAccount;
use App\Models\User;
use CloudCreativity\LaravelStripe\Events\AccountDeauthorized;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use Illuminate\Support\Facades\Event;
use Stripe\OAuth;
use Stripe\StripeObject;
use function Tests\actingAs;

test('disconnect org stripe account, org must exist', function () {
    $response = $this->put(route('use.submit.disconnect-stripe-account', ['org' => 'blah']));
    $response->assertStatus(404);
})->skip();

test('disconnect org stripe account requires login', function () {
    $user = User::factory()->create();
    $org = $user->organisations()->create(['name' => 'org']);

    $response = $this->put(route('use.submit.disconnect-stripe-account', ['org' => $org]));

    $response->assertRedirect(route('login'));
})->skip();

test('user cannot disconnect stripe account if not a member of the org', function () {
    $user = User::factory()->create();
    $org = $user->organisations()->create(['name' => 'org']);

    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $org->id]);

    $otherUser = User::factory()->create();

    // Just in case...
    Event::fake();
    Stripe::fake($expected = new StripeObject());

    $response = actingAs($otherUser)->put(route('use.submit.disconnect-stripe-account', ['org' => $org]));

    $response->assertStatus(403);
})->skip();


test('user can disconnect stripe account if member of the org', function () {
    $user = User::factory()->create();
    $org = $user->organisations()->create(['name' => 'org']);

    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $org->id]);

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
