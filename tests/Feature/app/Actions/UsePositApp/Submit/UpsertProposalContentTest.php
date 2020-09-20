<?php

use App\Actions\Team\CreateDraftProposal;
use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;

test('updating proposal content requires proposal exist', function () {
    $response = $this->put(route('use.submit.upsert-proposal-content', ['proposal' => 'blah']));
    $response->assertStatus(404);
});

test('updating proposal content requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-proposal-content', ['proposal' => $proposal]));
    $response->assertRedirect(route('login'));
});

test('user cannot update proposal content if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-proposal-content', ['proposal' => $proposal]));

    $response->assertStatus(403);
});

test('user can update proposal content if a team member', function () {
    // TODO this isn't production ready; need to validate inputs etc...
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $proposalContent = [
        'hi' => 'hello'
    ];

    $response = actingAs($user)->put(
        route('use.submit.upsert-proposal-content', ['proposal' => $proposal]),
        $proposalContent
    );

    $response->assertStatus(200);
    $this->assertEquals($proposalContent, $proposal->proposalContent->content);
});
