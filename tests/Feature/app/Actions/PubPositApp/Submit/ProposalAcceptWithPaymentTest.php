<?php

use App\Http\PublicPositAccessCookie;
use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\StripeAccount;
use App\Models\StripeCheckoutSession;
use App\Models\Team;
use App\Models\TeamContact;
use CloudCreativity\LaravelStripe\Facades\Stripe;

it('cannot accept proposal that is non-existant', function () {
    $response = $this->put(route('pub.posit.accept-with-payment', 'blah'));
    $response->assertStatus(404);
});

it('cannot accept proposal if no proposal access cookie', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    $response = $this->put(route('pub.posit.accept-with-payment', $posit));
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
});

it('cannot accept proposal if invalid proposal access cookie (not the recipient)', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);

    $cookie = PublicPositAccessCookie::create($contact);
    $cookieName = PublicPositAccessCookie::cookieName($team);

    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->put(route('pub.posit.accept-with-payment', $posit));

    $response = $this->put(route('pub.posit.accept-with-payment', $posit));
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
});

it('does not create new stripe checkout session if one exists already', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $depositPayment = PositPayment::factory()->create([
        'type' => PositPayment::TYPE_DEPOSIT,
        'posit_id' => $posit->id,
        'amount' => 1
    ]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $team->id]);
    $stripeCheckoutSession = StripeCheckoutSession::factory()->make([
        'stripe_account_id' => $stripeAccount->id
    ]);

    $posit->recipients()->sync([$contact->id]);
    $posit->setStatus(Posit::STATUS_PUBLISHED);
    $depositPayment->stripeCheckoutSession()->save($stripeCheckoutSession);
    $posit->refresh();


    $cookie = PublicPositAccessCookie::create($contact);
    $cookieName = PublicPositAccessCookie::cookieName($team);

    // Just in case, dont want to hit stripe api even if by mistake
    Stripe::fake(
        $expected = new \Stripe\Checkout\Session()
    );

    $checkoutSessionCountBefore = StripeCheckoutSession::count();

    $response = $this->withCookies([
        $cookieName => $cookie->getValue(),
    ])->put(route('pub.posit.accept-with-payment', $posit));

    assertEquals($checkoutSessionCountBefore, StripeCheckoutSession::count());

    $response->assertStatus(200);
    $response->assertJson([
        'stripe_account_id' => $stripeAccount->id,
        'stripe_checkout_session_id' => $stripeCheckoutSession->id
    ]);
});


it('creates new stripe checkout session if one does not exist', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $team->id]);
    $depositPayment = PositPayment::factory()->create([
        'type' => PositPayment::TYPE_DEPOSIT,
        'posit_id' => $posit->id,
        'amount' => 1
    ]);

    $posit->recipients()->sync([$contact->id]);
    $posit->setStatus(Posit::STATUS_PUBLISHED);
    $posit->refresh();

    $cookie = PublicPositAccessCookie::create($contact);
    $cookieName = PublicPositAccessCookie::cookieName($team);

    // Just in case, dont want to hit stripe api even if by mistake
    Stripe::fake(
        $expected = new \Stripe\Checkout\Session('cs_test_xxx')
    );

    $checkoutSessionCountBefore = StripeCheckoutSession::count();

    $response = $this->withCookies([
        $cookieName => $cookie->getValue(),
    ])->withCredentials()->putJson(route('pub.posit.accept-with-payment', $posit));

    assertEquals($checkoutSessionCountBefore + 1, StripeCheckoutSession::count());

    $response->assertStatus(200);
    $response->assertJson([
        'stripe_account_id' => $stripeAccount->id,
        'stripe_checkout_session_id' => StripeCheckoutSession::latest()->first()->id
    ]);

    $this->assertDatabaseHas('stripe_checkout_sessions', [
        'id' => 'cs_test_xxx',
        'stripe_account_id' => $stripeAccount->id,
        'model_type' => 'posit_payment',
        'model_id' => $depositPayment->id,
    ]);
});
