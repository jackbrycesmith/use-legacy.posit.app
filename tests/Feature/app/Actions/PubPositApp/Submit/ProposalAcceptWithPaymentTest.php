<?php

use App\Http\PublicProposalAccessCookie;
use App\Models\Proposal;
use App\Models\ProposalPayment;
use App\Models\StripeAccount;
use App\Models\StripeCheckoutSession;
use App\Models\Team;
use App\Models\TeamContact;
use CloudCreativity\LaravelStripe\Facades\Stripe;

it('cannot accept proposal that is non-existant', function () {
    $response = $this->put(route('pub.proposal.accept-with-payment', 'blah'));
    $response->assertStatus(404);
});

it('cannot accept proposal if no proposal access cookie', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);

    $response = $this->put(route('pub.proposal.accept-with-payment', $proposal));
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
});

it('cannot accept proposal if invalid proposal access cookie (not the recipient)', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);

    $cookie = PublicProposalAccessCookie::create($contact);
    $cookieName = PublicProposalAccessCookie::cookieName($team);

    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->put(route('pub.proposal.accept-with-payment', $proposal));

    $response = $this->put(route('pub.proposal.accept-with-payment', $proposal));
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
});

it('does not create new stripe checkout session if one exists already', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $depositPayment = ProposalPayment::factory()->create([
        'type' => ProposalPayment::TYPE_DEPOSIT,
        'proposal_id' => $proposal->id,
        'amount' => 1
    ]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $team->id]);
    $stripeCheckoutSession = StripeCheckoutSession::factory()->make([
        'stripe_account_id' => $stripeAccount->id
    ]);

    $proposal->recipients()->sync([$contact->id]);
    $proposal->setStatus(Proposal::STATUS_PUBLISHED);
    $depositPayment->stripeCheckoutSession()->save($stripeCheckoutSession);
    $proposal->refresh();


    $cookie = PublicProposalAccessCookie::create($contact);
    $cookieName = PublicProposalAccessCookie::cookieName($team);

    // Just in case, dont want to hit stripe api even if by mistake
    Stripe::fake(
        $expected = new \Stripe\Checkout\Session()
    );

    $checkoutSessionCountBefore = StripeCheckoutSession::count();

    $response = $this->withCookies([
        $cookieName => $cookie->getValue(),
    ])->put(route('pub.proposal.accept-with-payment', $proposal));

    assertEquals($checkoutSessionCountBefore, StripeCheckoutSession::count());

    $response->assertStatus(200);
    $response->assertJson([
        'stripe_account_id' => $stripeAccount->id,
        'stripe_checkout_session_id' => $stripeCheckoutSession->id
    ]);
});


it('creates new stripe checkout session if one does not exist', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $stripeAccount = StripeAccount::factory()->create(['owner_id' => $team->id]);
    $depositPayment = ProposalPayment::factory()->create([
        'type' => ProposalPayment::TYPE_DEPOSIT,
        'proposal_id' => $proposal->id,
        'amount' => 1
    ]);

    $proposal->recipients()->sync([$contact->id]);
    $proposal->setStatus(Proposal::STATUS_PUBLISHED);
    $proposal->refresh();

    $cookie = PublicProposalAccessCookie::create($contact);
    $cookieName = PublicProposalAccessCookie::cookieName($team);

    // Just in case, dont want to hit stripe api even if by mistake
    Stripe::fake(
        $expected = new \Stripe\Checkout\Session('cs_test_xxx')
    );

    $checkoutSessionCountBefore = StripeCheckoutSession::count();

    $response = $this->withCookies([
        $cookieName => $cookie->getValue(),
    ])->withCredentials()->putJson(route('pub.proposal.accept-with-payment', $proposal));

    assertEquals($checkoutSessionCountBefore + 1, StripeCheckoutSession::count());

    $response->assertStatus(200);
    $response->assertJson([
        'stripe_account_id' => $stripeAccount->id,
        'stripe_checkout_session_id' => StripeCheckoutSession::latest()->first()->id
    ]);

    $this->assertDatabaseHas('stripe_checkout_sessions', [
        'id' => 'cs_test_xxx',
        'stripe_account_id' => $stripeAccount->id,
        'model_type' => 'proposal_payment',
        'model_id' => $depositPayment->id,
    ]);
});
