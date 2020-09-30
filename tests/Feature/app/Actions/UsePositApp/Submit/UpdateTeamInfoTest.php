<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob;
use function Tests\actingAs;

test('team must exist to update info', function () {
    $response = $this->put(route('teams.update-info', ['team' => 'blah']));

    $response->assertStatus(404);
});

test('requires login to update team info', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $response = $this->put(route('teams.update-info', ['team' => $team]));

    $response->assertRedirect(route('login'));
});

test('to update team info, you must be part of the team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $otherUser = User::factory()->create();

    $response = actingAs($otherUser)->put(route('teams.update-info', ['team' => $team]));
    $response->assertStatus(403);
});

test('to update team info, must have name param', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $response = actingAs($user)->putJson(
        route('teams.update-info', ['team' => $team]),
        []
    );
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name' => 'The name field is required.']);
});

it('can update team name', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $response = actingAs($user)->putJson(
        route('teams.update-info', ['team' => $team]),
        [
            'name' => 'Team name'
        ]
    );

    $response->assertStatus(303);
    $this->assertDatabaseHas('teams', [
        'id' => $team->id,
        'name' => 'Team name'
    ]);
});

it('can update team name & logo', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    Bus::fake();
    $response = actingAs($user)->putJson(
        route('teams.update-info', ['team' => $team]),
        [
            'name' => 'Team name',
            'logo' => UploadedFile::fake()->image('logo.jpg', 100, 100)->size(100)
        ]
    );

    Bus::assertDispatched(PerformConversionsJob::class);
    $response->assertStatus(303);
    $this->assertDatabaseHas('teams', [
        'id' => $team->id,
        'name' => 'Team name'
    ]);
});
