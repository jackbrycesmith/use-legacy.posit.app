<?php

use App\Actions\Team\CreateDraftPosit;
use App\Models\Posit;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use function Tests\actingAs;

test('updating proposal content requires proposal exist', function () {
    $response = $this->put(route('use.submit.upsert-posit-content', ['posit' => 'blah']));
    $response->assertStatus(404);
});

test('updating proposal content requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-posit-content', ['posit' => $posit]));
    $response->assertRedirect(route('login'));
});

test('user cannot update proposal content if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-posit-content', ['posit' => $posit]));

    $response->assertStatus(403);
});

test('user cannot update proposal content in certain statuses', function ($status) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    Event::fake();
    $posit->setStatus($status);

    $positContent = [
        'hi' => 'hello'
    ];

    $response = actingAs($user)->put(
        route('use.submit.upsert-posit-content', ['posit' => $posit]),
        $positContent
    );

    $response->assertStatus(403);
})->with([
    ...Posit::CANNOT_UPDATE_STATUSES
]);

test('user can update proposal content if a team member', function () {
    // TODO this isn't production ready; need to validate inputs etc...
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $positContent = [
        'hi' => 'hello'
    ];

    $response = actingAs($user)->put(
        route('use.submit.upsert-posit-content', ['posit' => $posit]),
        $positContent
    );

    $response->assertStatus(200);
    $this->assertEquals($positContent, $posit->positContent->content);
});
