<?php

use App\Http\PublicProposalAccessCookie;
use App\Models\Organisation;
use App\Models\OrganisationContact;
use App\Models\Proposal;

it('requires valid proposal to be in url', function () {
    $response = $this->postJson(
        route('pub.proposal.submit.proposal-auth-cookie', 'sdfsfsd'),
        ['access_code' => 'blah']
    );

    $response->assertStatus(404);
});

it('requires valid input to proceed', function () {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);

    $response = $this->postJson(route('pub.proposal.submit.proposal-auth-cookie', $proposal));
    $response->assertStatus(422);
});

it('requires valid recipient access code', function () {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);
    $contact1 = factory(OrganisationContact::class)->create(['organisation_id' => $org->id, 'access_code' => 'sdf']);
    $proposal->recipients()->sync([$contact->id]);

    $response = $this->postJson(
        route('pub.proposal.submit.proposal-auth-cookie', $proposal),
        ['access_code' => $contact1->access_code]
    );

    $response->assertStatus(422);
});

it('redirects to proposal view on valid recipient access code', function () {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);
    $contact1 = factory(OrganisationContact::class)->create(['organisation_id' => $org->id, 'access_code' => 'sdf']);
    $proposal->recipients()->sync([$contact->id]);

    $response = $this->postJson(
        route('pub.proposal.submit.proposal-auth-cookie', $proposal),
        ['access_code' => $contact->access_code]
    );

    $response->assertRedirect(route('pub.proposal.view', $proposal));
    $response->assertCookie(PublicProposalAccessCookie::cookieName($org));
});
