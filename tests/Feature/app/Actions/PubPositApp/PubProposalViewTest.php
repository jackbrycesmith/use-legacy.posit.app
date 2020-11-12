<?php

use App\Models\Proposal;
use App\Models\Team;

it('404s if proposal does not exist', function () {
    $response = $this->get(route('pub.proposal.view', 'blah'));
    $response->assertStatus(404);
});

it('404s if proposal is in empty status', function () {
    $team = Team::factory()->create();

    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $proposal->deleteStatus($proposal->status);
    assertEmpty($proposal->status);

    $response = $this->get(route('pub.proposal.view', $proposal));
    $response->assertStatus(404);
});

it('requires auth cookie if public proposal is in state', function ($status) {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);

    $proposal->setStatus($status);
    $proposal->refresh();

    $response = $this->get(route('pub.proposal.view', $proposal));
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
})->with([
    ...Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES,
]);

it('allows access public proposal in reduced/limited state; ', function ($status) {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $response = $this->get(route('pub.proposal.view', $proposal));
    $response->assertStatus(200);
})->with(Proposal::PUBLIC_ACCESS_AUTH_BYPASS_STATUSES);
