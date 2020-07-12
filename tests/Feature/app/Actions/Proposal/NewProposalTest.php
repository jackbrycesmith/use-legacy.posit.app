<?php

use App\Models\Proposal;
use App\Models\User;
use function Tests\actingAs;
use function Tests\assertInertiaComponent;

it('shows new ProposalNewTryout page if not logged in', function () {
    $proposalCountBefore = Proposal::count();
    $response = $this->get('/proposals/new');
    $response->assertStatus(200);
    assertInertiaComponent($response, 'Use/ProposalNewTryout');
    assertEquals($proposalCountBefore, Proposal::count()); // Double checking it doesn't increase
});

it('creates proposal if user is member of one org, then redirects to view proposal details page', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']); // TODO? make organisation creation automatic for personal?

    assertEquals(0, $org->proposals()->count());
    $response = actingAs($user)->get('/proposals/new');
    assertEquals(1, $org->proposals()->count());

    $proposal = $org->proposals->first();
    $response->assertRedirect(route('use.proposal.view', ['proposal' => $proposal->uuid]));
});
