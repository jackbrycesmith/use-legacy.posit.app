<?php

use App\Models\Posit;
use App\Models\Team;

it('404s if proposal does not exist', function () {
    $response = $this->get(route('pub.posit.view', 'blah'));
    $response->assertStatus(404);
});

it('404s if proposal is in empty status', function () {
    $team = Team::factory()->create();

    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $posit->deleteStatus($posit->status);
    assertEmpty($posit->status);

    $response = $this->get(route('pub.posit.view', $posit));
    $response->assertStatus(404);
});

it('requires auth cookie if public proposal is in state', function ($status) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    $posit->setStatus($status);
    $posit->refresh();

    $response = $this->get(route('pub.posit.view', $posit));
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
})->with([
    ...Posit::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES,
]);

it('allows access public proposal in reduced/limited state; ', function ($status) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $posit->setStatus($status);
    $posit->refresh();

    $response = $this->get(route('pub.posit.view', $posit));
    $response->assertStatus(200);
})->with(Posit::PUBLIC_ACCESS_AUTH_BYPASS_STATUSES);
