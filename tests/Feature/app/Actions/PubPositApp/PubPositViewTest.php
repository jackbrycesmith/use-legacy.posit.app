<?php

use App\Models\Posit;
use App\Models\States\Posit\PositState;
use App\Models\Team;

it('404s if proposal does not exist', function () {
    $response = $this->get(route('pub.posit.view', 'blah'));
    $response->assertStatus(404);
});

it('requires auth cookie if public proposal is in state', function ($state) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

    $response = $this->get(route('pub.posit.view', $posit));
    $response->assertRedirect(route('pub.posit.view.auth', $posit));
})->with(
    PositState::all()->except(PositState::statesThatCanBypassPublicAuthAccess())->keys(),
);

it('allows access public proposal in reduced/limited state;', function ($state) {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

    $response = $this->get(route('pub.posit.view', $posit));
    $response->assertStatus(200);
})->with(
    PositState::statesThatCanBypassPublicAuthAccess()
);
