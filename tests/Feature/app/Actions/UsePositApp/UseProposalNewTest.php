<?php

use App\Models\Proposal;
use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;
use function Tests\assertInertiaComponent;

it('shows new ProposalNewTryout page if not logged in', function () {
    $proposalCountBefore = Proposal::count();
    $response = $this->get(route('use.proposal.new'));
    $response->assertStatus(200);
    assertInertiaComponent($response, 'Use/ProposalNew');
    assertEquals($proposalCountBefore, Proposal::count()); // Double checking it doesn't increase
});

it('creates proposal if user is member of one org, then redirects to view proposal details page', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    assertEquals(0, $team->proposals()->count());
    $response = actingAs($user)->get(route('use.proposal.new'));
    assertEquals(1, $team->proposals()->count());

    $proposal = $team->proposals->first();
    $response->assertRedirect(route('use.proposal.view', ['proposal' => $proposal->uuid]));
});

it('redirects to choose organisation page, if user is a member of multiple orgs', function () {
    $proposalCountBefore = Proposal::count();
    $user = User::factory()->create();
    $team1 = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $team2 = Team::factory()->create(['user_id' => $user->id, 'personal_team' => false]);

    $response = actingAs($user)->get(route('use.proposal.new'));
    $response->assertRedirect(route('use.proposal.new.choose-org'));
    assertEquals($proposalCountBefore, Proposal::count());
});
