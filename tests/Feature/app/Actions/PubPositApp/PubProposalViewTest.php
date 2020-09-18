<?php

use App\Models\Proposal;
use App\Models\Team;

it('cannot find public proposal page', function () {
    $response = $this->get(route('pub.proposal.view', 'blah'));
    $response->assertStatus(404);
});

it('requires auth cookie if public proposal is in state', function ($status) {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $response = $this->get(route('pub.proposal.view', $proposal));
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
})->with(Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES);

it('allows access public proposal in reduced/limited state; ', function ($status) {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $response = $this->get(route('pub.proposal.view', $proposal));
    $response->assertStatus(200);
})->with(Proposal::PUBLIC_ACCESS_AUTH_BYPASS_STATUSES);
