<?php

use App\Models\OrganisationMember;
use App\Models\Proposal;
use App\Models\ProposalPayment;
use App\Models\ProposalUser;
use App\Models\Team;
use App\Models\TeamContact;
use App\Models\User;

test('proposal has users via the team', function () {
    $team = Team::factory()->create();
    $team = User::factory()->create();


    $user = User::factory()->create();
    $user2 = User::factory()->create();
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
})->skip();

test('proposal has a recipient', function () {
    $team = Team::factory()->create();
    $proposal = $team->proposals()->create();

    $teamContact = TeamContact::factory()->create([
        'team_id' => $team->id
    ]);

    $teamContact2 = TeamContact::factory()->create([
        'team_id' => $team->id,
        'created_at' => now()->addMinute()
    ]);

    $proposal->recipients()->sync([$teamContact->id, $teamContact2->id]);

    assertEquals(2, $proposal->recipients()->count());
    assertEquals([
        'id' => $teamContact2->id,
        'uuid' => $teamContact2->uuid,
    ],
    [
        'id' => $proposal->recipient->id,
        'uuid' => $proposal->recipient->uuid,
    ]);
});

test('proposal cannot get recipient for wrong given access code', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $contact2 = TeamContact::factory()->create(['team_id' => $team->id]);
    $proposal->recipients()->sync([$contact->id, $contact2->id]);

    $recipient = $proposal->recipientForAccessCode('wrong');
    assertNull($recipient);
});

test('proposal can get recipient for given access code', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $contact2 = TeamContact::factory()->create(['team_id' => $team->id]);
    $proposal->recipients()->sync([$contact->id, $contact2->id]);

    $recipient = $proposal->recipientForAccessCode($contact->access_code);
    assertInstanceOf(TeamContact::class, $recipient);
    assertEquals($contact->id, $recipient->id);
});

test('proposal has a deposit payment', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);

    $payment = $proposal->payments()->create([
        'type' => null
    ]);

    $depositPayment = $proposal->payments()->create([
        'type' => ProposalPayment::TYPE_DEPOSIT
    ]);

    assertInstanceOf(ProposalPayment::class, $proposal->depositPayment);
    assertEquals($depositPayment->id, $proposal->depositPayment->id);

});

