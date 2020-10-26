<?php

use App\Actions\Team\CreateDraftProposal;
use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;

test('proposal must exist to update the name', function () {
    $response = $this->put(route('use.submit.upsert-proposal-name', [
        'proposal' => 'blah'
    ]));

    $response->assertStatus(404);
});

test('updating proposal name requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-proposal-name', [
        'proposal' => $proposal
    ]));

    $response->assertRedirect(route('login'));
});

test('user cannot update proposal name if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-proposal-name', ['proposal' => $proposal]));

    $response->assertStatus(403);
});

test('user cannot update proposal name', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-name', ['proposal' => $proposal]),
        [
            'name' => 'Name'
        ]
    );

    $response->assertStatus(204);
        $this->assertDatabaseHas('proposals', [
        'id' => $proposal->id,
        'name' => 'Name',
    ]);
});
