<?php

use App\Http\Middleware\CheckPublicProposalStatefulAccess;
use App\Http\PublicProposalAccessCookie;
use App\Models\Organisation;
use App\Models\OrganisationContact;
use App\Models\Proposal;
use Illuminate\Support\Facades\Route;

test('if no proposal resolved, then 404 response', function () {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $response = $this->get("/test-proposal-access/abc");
    $response->assertStatus(404);
});

test('skip access check if proposal in status', function ($status) {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $proposal->setStatus($status);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $response = $this->get("/test-proposal-access/{$proposal->uuid}");
    $response->assertStatus(200);
})
->with(Proposal::PUBLIC_ACCESS_AUTH_BYPASS_STATUSES);

test('if no valid proposal access cookie, redirect to public proposal auth page', function ($status) {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $proposal->setStatus($status);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $response = $this->get("/test-proposal-access/{$proposal->uuid}");
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
})
->with(Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);

test('cannot access if authed for organisation, but not a recipient of the requested proposal', function ($status) {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $cookie = PublicProposalAccessCookie::create($contact);
    $cookieName = PublicProposalAccessCookie::cookieName($org);

    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->get("/test-proposal-access/{$proposal->uuid}");

    $response = $this->get("/test-proposal-access/{$proposal->uuid}");
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
})
->with(Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);

test('if valid proposal access cookie, can continue request', function ($status) {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);
    $proposal->recipients()->sync([$contact->id]);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $cookie = PublicProposalAccessCookie::create($contact);
    $cookieName = PublicProposalAccessCookie::cookieName($org);

    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->get("/test-proposal-access/{$proposal->uuid}");

    $response = $this->get("/test-proposal-access/{$proposal->uuid}");
    $response->assertStatus(200);
})
->with(Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);
