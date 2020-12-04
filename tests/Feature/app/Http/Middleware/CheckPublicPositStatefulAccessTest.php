<?php

use App\Http\Middleware\CheckPublicPositStatefulAccess;
use App\Http\PublicPositAccessCookie;
use App\Models\Posit;
use App\Models\Team;
use App\Models\TeamContact;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

test('if no proposal resolved, then 404 response', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    Route::get('/test-proposal-access/{posit:uuid}', function (Posit $posit) {
        return 'Hello World';
    })
    ->middleware(CheckPublicPositStatefulAccess::class);

    $response = $this->get("/test-proposal-access/" . Str::uuid());
    $response->assertStatus(404);
});

test('skip access check if proposal in status', function ($status) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $posit->setStatus($status);

    Route::get('/test-proposal-access/{posit:uuid}', function (Posit $posit) {
        return 'Hello World';
    })
    ->middleware(CheckPublicPositStatefulAccess::class);

    $response = $this->get("/test-proposal-access/{$posit->uuid}");
    $response->assertStatus(200);
})
->with(Posit::PUBLIC_ACCESS_AUTH_BYPASS_STATUSES);

test('if no valid proposal access cookie, redirect to public proposal auth page', function ($status) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $posit->setStatus($status);

    Route::get('/test-proposal-access/{posit:uuid}', function (Posit $posit) {
        return 'Hello World';
    })
    ->middleware(CheckPublicPositStatefulAccess::class);

    $response = $this->get("/test-proposal-access/{$posit->uuid}");
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
})
->with(Posit::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);

test('ignore-status-check', function () {
    // TODO: tests & consider refactor of recently added 'ignore-status-check' param
})->skip();

test('cannot access if authed for team, but not a recipient of the requested proposal', function ($status) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $posit->setStatus($status);
    $posit->refresh();

    $contact = TeamContact::factory()->create(['team_id' => $team->id]);

    Route::get('/test-proposal-access/{posit:uuid}', function (Posit $posit) {
        return 'Hello World';
    })
    ->middleware(CheckPublicPositStatefulAccess::class);

    $cookie = PublicPositAccessCookie::create($contact);
    $cookieName = PublicPositAccessCookie::cookieName($team);

    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->get("/test-proposal-access/{$posit->uuid}");

    $response = $this->get("/test-proposal-access/{$posit->uuid}");
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
})
->with(Posit::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);

test('if valid proposal access cookie, can continue request', function ($status) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $posit->setStatus($status);
    $posit->refresh();

    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $posit->recipients()->sync([$contact->id]);

    Route::get('/test-proposal-access/{posit:uuid}', function (Posit $posit) {
        return 'Hello World';
    })
    ->middleware(CheckPublicPositStatefulAccess::class);

    $cookie = PublicPositAccessCookie::create($contact);
    $cookieName = PublicPositAccessCookie::cookieName($team);

    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->get("/test-proposal-access/{$posit->uuid}");

    $response = $this->get("/test-proposal-access/{$posit->uuid}");
    $response->assertStatus(200);
})
->with(Posit::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);
