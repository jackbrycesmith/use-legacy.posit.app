<?php

use App\Actions\Team\CreateDraftPosit;
use App\Models\Team;
use App\Models\TeamContact;
use App\Models\User;
use function Tests\actingAs;

// Adding Proposal...
test('adding proposal recipient, proposal must exist', function () {
    $response = $this->post(route('use.posit.recipients.add-submit', ['posit' => 'blah']));
    $response->assertStatus(404);
});

test('adding proposal recipient requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->post(route('use.posit.recipients.add-submit', ['posit' => $posit]));

    $response->assertRedirect(route('login'));
});

test('user cannot add proposal recipient if no name supplied', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->postJson(
        route('use.posit.recipients.add-submit', ['posit' => $posit])
    );

    $response->assertStatus(422);
    assertEquals(0, $posit->recipients()->count());
});

test('user cannot add proposal recipient if not a member of team owning the proposal', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();

    $response = actingAs($otherUser)->post(route('use.posit.recipients.add-submit', ['posit' => $posit]), ['name' => 'Test Name']);

    $response->assertStatus(403);
    assertEquals(0, $posit->recipients()->count());
});

test('user can add proposal recipient', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->post(
        route('use.posit.recipients.add-submit', ['posit' => $posit]),
        ['name' => 'Recipient Name']
    );

    $response->assertStatus(201);
    assertEquals(1, $posit->recipients()->count());
});

// Updating proposal...

test('updating proposal recipient, proposal & recipient must exist', function () {
    $response = $this->put(route('use.posit.recipients.update', ['posit' => 'blah', 'recipient' => 'blah']));

    $response->assertStatus(404);
});

test('updating proposal recipient requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.posit.recipients.update', [
        'posit' => $posit, 'recipient' => $contact
    ]));

    $response->assertRedirect(route('login'));
});

test('user cannot update the proposal recipient if not a member of team owning the proposal', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();

    $response = actingAs($otherUser)->put(
        route('use.posit.recipients.update', ['posit' => $posit, 'recipient' => $contact->id])
    );

    $response->assertStatus(403);
    assertEquals(0, $posit->recipients()->count());
});

test('user cannot update the proposal recipient if contact is not from the proposal team', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);

    $otherTeam = Team::factory()->create();
    $otherTeam = TeamContact::factory()->create(['team_id' => $otherTeam->id]);

    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->put(
        route('use.posit.recipients.update', ['posit' => $posit, 'recipient' => $otherTeam->id])
    );

    $response->assertStatus(403);
    assertEquals(0, $posit->recipients()->count());
});

test('user can update the proposal recipient', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->put(
        route('use.posit.recipients.update', ['posit' => $posit, 'recipient' => $contact->id])
    );

    $response->assertStatus(200);
    assertEquals(1, $posit->recipients()->count());
    assertEquals($contact->id, $posit->recipient->id);
});
