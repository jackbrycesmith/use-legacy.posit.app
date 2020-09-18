<?php

use App\Http\Middleware\CheckPublicProposalStatefulAccess;
use App\Http\PublicProposalAccessCookie;
use App\Models\Proposal;
use App\Models\Team;
use App\Models\TeamContact;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

test('if no proposal resolved, then 404 response', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $response = $this->get("/test-proposal-access/" . Str::uuid());
    $response->assertStatus(404);
});

test('skip access check if proposal in status', function ($status) {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
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
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $proposal->setStatus($status);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $response = $this->get("/test-proposal-access/{$proposal->uuid}");
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
})
->with(Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);

test('cannot access if authed for team, but not a recipient of the requested proposal', function ($status) {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $contact = TeamContact::factory()->create(['team_id' => $team->id]);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $cookie = PublicProposalAccessCookie::create($contact);
    $cookieName = PublicProposalAccessCookie::cookieName($team);

    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->get("/test-proposal-access/{$proposal->uuid}");

    $response = $this->get("/test-proposal-access/{$proposal->uuid}");
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
})
->with(Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);

test('if valid proposal access cookie, can continue request', function ($status) {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $proposal->recipients()->sync([$contact->id]);

    Route::get('/test-proposal-access/{proposal:uuid}', function (Proposal $proposal) {
        return 'Hello World';
    })
    ->middleware(CheckPublicProposalStatefulAccess::class);

    $cookie = PublicProposalAccessCookie::create($contact);
    $cookieName = PublicProposalAccessCookie::cookieName($team);

    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->get("/test-proposal-access/{$proposal->uuid}");

    $response = $this->get("/test-proposal-access/{$proposal->uuid}");
    $response->assertStatus(200);
})
->with(Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);
