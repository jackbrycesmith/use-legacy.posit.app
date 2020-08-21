<?php

use App\Models\Organisation;
use App\Models\OrganisationContact;
use App\Models\OrganisationMember;
use App\Models\Proposal;
use App\Models\ProposalUser;
use App\Models\User;

test('proposal has users via the organisation', function () {
    $user = factory(User::class)->create();
    $user2 = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']);
    $otherOrgMember = OrganisationMember::create(['user_id' => $user2->id, 'organisation_id' => $org->id]);
    $proposal = $org->proposals()->create();
    $orgMemberId = OrganisationMember::where('user_id', $user->id)->first()->id;

    assertEquals(0, $proposal->users()->count());
    ProposalUser::create(['proposal_id' => $proposal->id, 'organisation_member_id' => $orgMemberId]);
    ProposalUser::create(['proposal_id' => $proposal->id, 'organisation_member_id' => $otherOrgMember->id]);

    assertEquals(2, $proposal->users()->count());
    assertInstanceOf(User::class, $proposal->users()->first());
    $userIds = $proposal->users->pluck('id')->toArray();
    assertContains($user->id, $userIds);
    assertContains($user2->id, $userIds);
});

test('proposal has a recipient', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']);
    $proposal = $org->proposals()->create();

    $orgContact = factory(OrganisationContact::class)->create([
        'organisation_id' => $org->id
    ]);

    $orgContact2 = factory(OrganisationContact::class)->create([
        'organisation_id' => $org->id,
        'created_at' => now()->addMinute()
    ]);

    $proposal->recipients()->sync([$orgContact->id, $orgContact2->id]);

    assertEquals(2, $proposal->recipients()->count());
    assertEquals([
        'id' => $orgContact2->id,
        'uuid' => $orgContact2->uuid,
    ],
    [
        'id' => $proposal->recipient->id,
        'uuid' => $proposal->recipient->uuid,
    ]);
});

test('proposal cannot get recipient for wrong given access code', function () {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);
    $contact2 = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);
    $proposal->recipients()->sync([$contact->id, $contact2->id]);

    $recipient = $proposal->recipientForAccessCode('wrong');
    assertNull($recipient);
});

test('proposal can get recipient for given access code', function () {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);
    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);
    $contact2 = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);
    $proposal->recipients()->sync([$contact->id, $contact2->id]);

    $recipient = $proposal->recipientForAccessCode($contact->access_code);
    assertInstanceOf(OrganisationContact::class, $recipient);
    assertEquals($contact->id, $recipient->id);
});
