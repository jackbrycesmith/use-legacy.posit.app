<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;
use function Tests\actingAs;
use function Tests\assertInertiaComponent;

it('404s if team is not in a valid uuid format', function ($teamUuid) {
    $response = $this->get(route('use.team.credits', [
        'team' => $teamUuid
    ]));

    $response->assertStatus(404);
})->with([
    'blah',
])->only();

it('redirects to login if valid uuid, but not logged in', function ($teamUuid) {
    $response = $this->get(route('use.team.credits', [
        'team' => $teamUuid
    ]));

    $response->assertRedirect(route('login'));
})->with([
    Str::uuid()->toString(),
])->only();

it('403s if user is not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $otherUser = User::factory()->create();

    $response = actingAs($otherUser)->get(route('use.team.credits', [
        'team' => $team
    ]));

    $response->assertStatus(403);
})->only();

it('allows access if user is a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    $response = actingAs($user)->get(route('use.team.credits', [
        'team' => $team
    ]));

    assertInertiaComponent($response, 'Use/TeamCredits');
})->only();
