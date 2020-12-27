<?php

use App\Http\PublicPositAccessCookie;
use App\Models\Posit;
use App\Models\States\Posit\Accepted;
use App\Models\States\Posit\Published;
use App\Models\Team;
use App\Models\TeamContact;
use App\Notifications\Team\TeamPositAccepted;
use Illuminate\Support\Facades\Notification;

it('cannot accept posit that is non-existant', function () {
    Notification::fake();
    $response = $this->put(route('pub.posit.accept', 'blah'));
    Notification::assertNothingSent();
    $response->assertStatus(404);
});

it('cannot accept posit if no posit access cookie', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    Notification::fake();
    $response = $this->put(route('pub.posit.accept', $posit));
    Notification::assertNothingSent();
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
});

it('cannot accept posit if invalid posit access cookie (not the recipient)', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);

    $cookie = PublicPositAccessCookie::create($contact);
    $cookieName = PublicPositAccessCookie::cookieName($team);

    Notification::fake();
    $response = $this->withUnencryptedCookies([
        $cookieName => $cookie->getValue(),
    ])->put(route('pub.posit.accept', $posit));
    Notification::assertNothingSent();

    $response = $this->put(route('pub.posit.accept', $posit));
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
});

it('transitions to accepted state', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create([
        'team_id' => $team->id,
        'state' => Published::class
    ]);

    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $posit->recipients()->sync([$contact->id]);
    $posit->refresh();

    $cookie = PublicPositAccessCookie::create($contact);
    $cookieName = PublicPositAccessCookie::cookieName($team);

    Notification::fake();
    $response = $this->withCookies([
        $cookieName => $cookie->getValue(),
    ])->put(route('pub.posit.accept', $posit));
    Notification::assertSentTo([$team], TeamPositAccepted::class);

    $posit->refresh();
    expect($posit->state)->toBeInstanceOf(Accepted::class);

    $response->assertStatus(204);
});
