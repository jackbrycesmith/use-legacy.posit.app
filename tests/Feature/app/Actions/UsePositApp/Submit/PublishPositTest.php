<?php

use App\Actions\Team\CreateDraftPosit;
use App\Models\Posit;
use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;

test('to publish proposal it must exist', function () {
    $response = $this->put(route('use.submit.publish-posit', ['posit' => 'blah']));
    $response->assertStatus(404);
});

test('to publish proposal it requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.publish-posit', ['posit' => $posit]));
    $response->assertRedirect(route('login'));
    assertFalse($posit->hasEverHadStatus(Posit::STATUS_PUBLISHED));
});

test('to publish proposal, user must be a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.publish-posit', ['posit' => $posit]));

    $response->assertStatus(403);
    assertFalse($posit->hasEverHadStatus(Posit::STATUS_PUBLISHED));
});

test('to publish proposal, it must not have already been published', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);
    $posit->setStatus(Posit::STATUS_PUBLISHED);
    $posit->save();

    $response = actingAs($user)->putJson(route('use.submit.publish-posit', ['posit' => $posit]));

    $response->assertStatus(403);
    // TODO why this isn't working?!
    // $response->assertJsonFragment([
    //     'message' => 'This proposal has already been published.'
    // ]);
});


test('can publish proposal', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(route('use.submit.publish-posit', ['posit' => $posit]));
    $response->assertStatus(204);

    // $posit->refresh();
    assertEquals(Posit::STATUS_PUBLISHED, $posit->status);
});
