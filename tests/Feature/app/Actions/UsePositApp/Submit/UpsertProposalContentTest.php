<?php

use App\Actions\Organisation\CreateDraftProposal;
use App\Models\User;
use function Tests\actingAs;

test('updating proposal content requires login', function () {
    $response = $this->put(route('use.submit.upsert-proposal-content', ['proposal' => 'blah']));

    $response->assertRedirect(route('login'));
});

test('user cannot update proposal content if not a proposal user', function () {
    $user = factory(User::class)->create();
    $otherUser = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($otherUser)->put(route('use.submit.upsert-proposal-content', ['proposal' => $proposal]));

    $response->assertStatus(403);
});

test('user can update proposal content if a proposal user', function () {
    // TODO this isn't production ready; need to validate inputs etc...
    $user = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $proposalContent = [
        'hi' => 'hello'
    ];

    $response = actingAs($user)->put(
        route('use.submit.upsert-proposal-content', ['proposal' => $proposal]),
        $proposalContent
    );

    $response->assertStatus(200);
    $this->assertEquals($proposalContent, $proposal->proposalContent->content);
});
