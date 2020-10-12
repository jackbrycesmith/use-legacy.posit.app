<?php

use App\Models\StripeAccount;
use App\Models\Team;
use App\Models\User;
use CloudCreativity\LaravelStripe\Events\AccountDeauthorized;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use Illuminate\Support\Facades\Event;
use Stripe\OAuth;
use Stripe\StripeObject;
use function Tests\actingAs;

test('disconnect team stripe account, team must exist', function () {
    $response = $this->put(route('use.submit.disconnect-stripe-account', ['team' => 'blah']));
    $response->assertStatus(404);
});

test('disconnect team stripe account requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $response = $this->put(route('use.submit.disconnect-stripe-account', ['team' => $team]));

    $response->assertRedirect(route('login'));
});

test('user cannot disconnect stripe account if not a member of the team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $team->id]);

    $otherUser = User::factory()->create();

    // Just in case...
    Event::fake();
    Stripe::fake($expected = new StripeObject());

    $response = actingAs($otherUser)->put(route('use.submit.disconnect-stripe-account', ['team' => $team]));

    $response->assertStatus(403);
});


test('user can disconnect stripe account if owner of the team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $team->id]);

    Event::fake();
    Stripe::fake($expected = new StripeObject());

    $response = actingAs($user)->put(route('use.submit.disconnect-stripe-account', ['team' => $team]));

    $response->assertStatus(303);
    $response->assertSessionHas('status', 'stripe-account-disconnected');

    Stripe::assertInvoked(OAuth::class, 'deauthorize', function ($params, $options) use ($stripeAccount) {
        $this->assertSame(['stripe_user_id' => $stripeAccount->id], $params, 'params');
        return true;
    });

    Event::assertDispatched(AccountDeauthorized::class, function ($event) use ($stripeAccount) {
        $this->assertTrue($stripeAccount->is($event->account), 'event account');
        return true;
    });
});
