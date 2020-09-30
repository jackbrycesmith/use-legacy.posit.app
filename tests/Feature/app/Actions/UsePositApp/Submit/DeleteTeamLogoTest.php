<?php

use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;

test('to delete team logo, team must exist', function () {
    $response = $this->delete(route('teams.delete-logo', ['team' => 'blah']));

    $response->assertStatus(404);
});

test('to delete team logo, requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $response = $this->delete(route('teams.delete-logo', ['team' => $team]));

    $response->assertRedirect(route('login'));
});

test('to delete team logo, you must be part of the team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $otherUser = User::factory()->create();

    $response = actingAs($otherUser)->delete(route('teams.delete-logo', ['team' => $team]));
    $response->assertStatus(403);
});

test('to delete team logo, you need to be a team owner', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $response = actingAs($user)->delete(route('teams.delete-logo', ['team' => $team]));
    $response->assertStatus(303);
    $response->assertSessionHas('status', 'team-logo-deleted');
});
