<?php

use App\Http\Middleware\CheckPublicPositStatefulAccess;
use App\Http\PublicPositAccessCookie;
use App\Models\Posit;
use App\Models\States\Posit\PositState;
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

test('skip access check if proposal in status', function ($state) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

    Route::get('/test-proposal-access/{posit:uuid}', function (Posit $posit) {
        return 'Hello World';
    })
    ->middleware(CheckPublicPositStatefulAccess::class);

    $response = $this->get("/test-proposal-access/{$posit->uuid}");
    $response->assertStatus(200);
})->with(
    PositState::statesThatCanBypassPublicAuthAccess()
);

test('if no valid proposal access cookie, redirect to public proposal auth page', function ($state) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

    Route::get('/test-proposal-access/{posit:uuid}', function (Posit $posit) {
        return 'Hello World';
    })
    ->middleware(CheckPublicPositStatefulAccess::class);

    $response = $this->get("/test-proposal-access/{$posit->uuid}");
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
})
->with(PositState::all()->except(PositState::statesThatCanBypassPublicAuthAccess())->keys());

test('ignore-status-check', function () {
    // TODO: tests & consider refactor of recently added 'ignore-status-check' param
})->skip();

test('cannot access if authed for team, but not a recipient of the requested proposal', function ($state) {
    $team = Team::factory()->create();
$posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

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
->with(PositState::all()->except(PositState::statesThatCanBypassPublicAuthAccess())->keys());

test('if valid proposal access cookie, can continue request', function ($state) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

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
->with(PositState::all()->except(PositState::statesThatCanBypassPublicAuthAccess())->keys());
