<?php

use App\Actions\Organisation\CreateDraftProposal;
use App\Models\User;
use function Tests\assertInertiaComponent;
use function Tests\actingAs;

test('user cannot get UseProposalView page if proposal does not exist', function () {
    $user = factory(User::class)->create();

    $response = actingAs($user)->get(route('use.proposal.view', ['proposal' => 'non-existant-proposal']));

    $response->assertStatus(404);
});

test('user who is not a proposaluser cannot get UseProposalView page', function () {
    $user = factory(User::class)->create();
    $otherUser = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($otherUser)->get(route('use.proposal.view', ['proposal' => $proposal]));

    $response->assertStatus(403);
});

test('user who is a proposaluser can get UseProposalView page', function () {
    $user = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($user)->get(route('use.proposal.view', ['proposal' => $proposal]));

    $response->assertStatus(200);
    assertInertiaComponent($response, 'Use/ProposalView');
});
