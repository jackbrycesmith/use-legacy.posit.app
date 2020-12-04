<?php

use App\Actions\Team\CreateDraftPosit;
use App\Models\Posit;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use function Tests\actingAs;

test('proposal must exist to update the name', function () {
    $response = $this->put(route('use.submit.upsert-posit-name', [
        'posit' => 'blah'
    ]));

    $response->assertStatus(404);
});

test('updating proposal name requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-posit-name', [
        'posit' => $posit
    ]));

    $response->assertRedirect(route('login'));
});

test('user cannot update proposal name if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-posit-name', ['posit' => $posit]));

    $response->assertStatus(403);
});

test('user cannot update proposal name in certain statuses', function ($status) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    Event::fake();
    $posit->setStatus($status);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-name', ['posit' => $posit]),
        [
            'name' => 'Name'
        ]
    );

    $response->assertStatus(403);
})->with([
    ...Posit::CANNOT_UPDATE_STATUSES
]);

test('user cann update proposal name', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-name', ['posit' => $posit]),
        [
            'name' => 'Name'
        ]
    );

    $response->assertStatus(204);
        $this->assertDatabaseHas('posits', [
        'id' => $posit->id,
        'name' => 'Name',
    ]);
});
