<?php

use App\Actions\Organisation\CreateDraftProposal;
use App\Models\Organisation;
use App\Models\OrganisationContact;
use App\Models\User;
use function Tests\actingAs;

// Adding Proposal...

test('adding proposal recipient requires login', function () {
    $response = $this->post(route('use.proposal.recipients.add-submit', ['proposal' => 'blah']));

    $response->assertRedirect(route('login'));
});

test('user cannot add proposal recipient if no name supplied', function () {
    $user = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($user)->postJson(
        route('use.proposal.recipients.add-submit', ['proposal' => $proposal])
    );

    $response->assertStatus(422);
    assertEquals(0, $proposal->recipients()->count());
});

test('user cannot add proposal recipient if not a proposal user', function () {
    $user = factory(User::class)->create();
    $otherUser = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($otherUser)->post(route('use.proposal.recipients.add-submit', ['proposal' => $proposal]), ['name' => 'Test Name']);

    $response->assertStatus(403);
    assertEquals(0, $proposal->recipients()->count());
});

test('user can add proposal recipient', function () {
    $user = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($user)->post(
        route('use.proposal.recipients.add-submit', ['proposal' => $proposal]),
        ['name' => 'Recipient Name']
    );

    $response->assertStatus(201);
    assertEquals(1, $proposal->recipients()->count());
});

// Updating proposal...

test('updating proposal recipient requires login', function () {
    $response = $this->put(route('use.proposal.recipients.update', ['proposal' => 'blah', 'recipient' => 'blah']));

    $response->assertRedirect(route('login'));
});

test('user cannot update the proposal recipient if not a proposal user', function () {
    $user = factory(User::class)->create();
    $otherUser = factory(User::class)->create();
    $org = $user->organisations->first();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $org
    ]);

    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);

    $response = actingAs($otherUser)->put(
        route('use.proposal.recipients.update', ['proposal' => $proposal, 'recipient' => $contact->id])
    );

    $response->assertStatus(403);
    assertEquals(0, $proposal->recipients()->count());
});

test('user cannot update the proposal recipient if contact is not from the proposal org', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations->first();

    $otherOrg = factory(Organisation::class)->create();
    $otherContact = factory(OrganisationContact::class)->create(['organisation_id' => $otherOrg->id]);

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $org
    ]);

    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);

    $response = actingAs($user)->put(
        route('use.proposal.recipients.update', ['proposal' => $proposal, 'recipient' => $otherContact->id])
    );

    $response->assertStatus(403);
    assertEquals(0, $proposal->recipients()->count());
});

test('user can update the proposal recipient', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations->first();
    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $org
    ]);

    $response = actingAs($user)->put(
        route('use.proposal.recipients.update', ['proposal' => $proposal, 'recipient' => $contact->id])
    );

    $response->assertStatus(200);
    assertEquals(1, $proposal->recipients()->count());
    assertEquals($contact->id, $proposal->recipient->id);
});
