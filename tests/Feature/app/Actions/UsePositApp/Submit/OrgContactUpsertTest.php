<?php

use App\Models\Team;
use App\Models\TeamContact;
use App\Models\User;
use Illuminate\Support\Arr;
use function Tests\actingAs;

test('user cannot update the contact if it does not belong to the team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $otherTeam = Team::factory()->create();
    $otherContact = TeamContact::factory()->create(['team_id' => $otherTeam->id]);

    $response = actingAs($user)->put(
        route('use.team.contacts.update', ['team' => $team, 'contact' => $otherContact->id]),
        [
            'name' => 'New name...'
        ]
    );

    $response->assertStatus(403);
    assertNotEquals('New name...', Arr::get($otherContact->refresh()->meta, 'name'));
});
