<?php

use App\Models\Organisation;
use App\Models\Proposal;

it('cannot find public proposal page', function () {
    $response = $this->get(route('pub.proposal.view', 'blah'));
    $response->assertStatus(404);
})->only();

it('requires auth cookie if public proposal is in state', function ($status) {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $response = $this->get(route('pub.proposal.view', $proposal));
    $response->assertRedirect(route('pub.proposal.view.auth', $proposal));
})
->with(Proposal::PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES)
->only();

it('allows access public proposal in reduced/limited state; ', function ($status) {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $proposal->setStatus($status);
    $proposal->refresh();

    $response = $this->get(route('pub.proposal.view', $proposal));
    $response->assertStatus(200);
})
->with(Proposal::PUBLIC_ACCESS_AUTH_BYPASS_STATUSES)
->only();
