<?php

use App\Http\PublicProposalAccessCookie;
use App\Models\Proposal;
use App\Models\Team;
use App\Models\TeamContact;

it('requires valid proposal to be in url', function () {
    $response = $this->postJson(
        route('pub.proposal.submit.proposal-auth-cookie', 'sdfsfsd'),
        ['access_code' => 'blah']
    );

    $response->assertStatus(404);
});

it('requires valid input to proceed', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);

    $response = $this->postJson(route('pub.proposal.submit.proposal-auth-cookie', $proposal));
    $response->assertStatus(422);
});

it('requires valid recipient access code', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $contact1 = TeamContact::factory()->create(['team_id' => $team->id, 'access_code' => 'sdf']);
    $proposal->recipients()->sync([$contact->id]);

    $response = $this->postJson(
        route('pub.proposal.submit.proposal-auth-cookie', $proposal),
        ['access_code' => $contact1->access_code]
    );

    $response->assertStatus(422);
});

it('redirects to proposal view on valid recipient access code', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $contact1 = TeamContact::factory()->create(['team_id' => $team->id, 'access_code' => 'sdf']);
    $proposal->recipients()->sync([$contact->id]);

    $response = $this->postJson(
        route('pub.proposal.submit.proposal-auth-cookie', $proposal),
        ['access_code' => $contact->access_code]
    );

    $response->assertRedirect(route('pub.proposal.view', $proposal));
    $response->assertCookie(PublicProposalAccessCookie::cookieName($team));
});
