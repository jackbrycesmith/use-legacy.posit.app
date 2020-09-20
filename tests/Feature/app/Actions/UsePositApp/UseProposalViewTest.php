<?php

use App\Actions\Team\CreateDraftProposal;
use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;
use function Tests\assertInertiaComponent;

test('user cannot get UseProposalView page if proposal does not exist', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->get(route('use.proposal.view', ['proposal' => 'non-existant-proposal']));

    $response->assertStatus(404);
});

test('user who is not a team member cannot get UseProposalView page', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->get(route('use.proposal.view', ['proposal' => $proposal]));

    $response->assertStatus(403);
});

test('user who is a team member can get UseProposalView page', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->get(route('use.proposal.view', ['proposal' => $proposal]));

    $response->assertStatus(200);
    assertInertiaComponent($response, 'Use/ProposalView');
});
