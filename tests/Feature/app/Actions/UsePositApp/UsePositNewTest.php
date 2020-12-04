<?php

use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;

it('redirects to login if not logged in', function () {
    $response = $this->get(route('use.posit.new'));

    $response->assertRedirect(route('login'));
});

it('creates posit for current team, then redirects to view posit details page', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    assertEquals(0, $team->posits()->count());
    $response = actingAs($user)->get(route('use.posit.new'));
    assertEquals(1, $team->posits()->count());

    $posit = $team->posits->first();
    $response->assertRedirect(route('use.posit.view', $posit));
});
