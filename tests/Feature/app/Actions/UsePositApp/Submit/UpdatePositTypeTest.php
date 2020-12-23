<?php

use App\Actions\Team\CreateDraftPosit;
use App\Enums\PositType;
use App\Models\Posit;
use App\Models\States\Posit\PositState;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use function Tests\actingAs;

test('updating posit type requires it exists', function () {
    $response = $this->put(route('use.submit.update-posit-type', ['posit' => 'blah']));
    $response->assertStatus(404);
})->only();

test('updating posit type requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.update-posit-type', ['posit' => $posit]));
    $response->assertRedirect(route('login'));
})->only();

test('user cannot update posit type if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.update-posit-type', ['posit' => $posit]));

    $response->assertStatus(403);
})->only();

test('user cannot update posit type when in certain states', function ($state) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

    Event::fake();

    $response = actingAs($user)->putJson(
        route('use.submit.update-posit-type', ['posit' => $posit]),
        [
            'type' => 'blah',
        ]
    );

    $response->assertStatus(403);
})->with(
    PositState::all()->except(PositState::statesThatCanUpdateThePosit())->keys()
)->only();

test('user cannot update posit type if missing params', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.update-posit-type', ['posit' => $posit]),
        [
            //
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'type' => 'The type field is required.'
    ]);

})->only();

test('user cannot update posit type if not in enum', function ($enumValue) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.update-posit-type', ['posit' => $posit]),
        [
            'type' => $enumValue
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'type' => 'The type field is not a valid App\Enums\PositType.'
    ]);

})->with([
    'blah'
])->only();

test('user can update posit type', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.update-posit-type', ['posit' => $posit]),
        [
            'type' => PositType::accept_and_pay()
        ]
    );

    $response->assertStatus(204);

    $this->assertDatabaseHas('posits', [
        'id' => $posit->id,
        'type' => PositType::accept_and_pay()
    ]);

})->only();
