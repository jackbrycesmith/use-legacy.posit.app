<?php

use App\Models\OrganisationMember;
use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\PositUser;
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
    $posit = $org->proposals()->create();
    $orgMemberId = OrganisationMember::where('user_id', $user->id)->first()->id;

    assertEquals(0, $posit->users()->count());
    PositUser::create(['posit_id' => $posit->id, 'organisation_member_id' => $orgMemberId]);
    PositUser::create(['posit_id' => $posit->id, 'organisation_member_id' => $otherOrgMember->id]);

    assertEquals(2, $posit->users()->count());
    assertInstanceOf(User::class, $posit->users()->first());
    $userIds = $posit->users->pluck('id')->toArray();
    assertContains($user->id, $userIds);
    assertContains($user2->id, $userIds);
})->skip();

test('proposal has a recipient', function () {
    $team = Team::factory()->create();
    $posit = $team->posits()->create();

    $teamContact = TeamContact::factory()->create([
        'team_id' => $team->id
    ]);

    $teamContact2 = TeamContact::factory()->create([
        'team_id' => $team->id,
        'created_at' => now()->addMinute()
    ]);

    $posit->recipients()->sync([$teamContact->id, $teamContact2->id]);

    assertEquals(2, $posit->recipients()->count());
    assertEquals([
        'id' => $teamContact2->id,
        'uuid' => $teamContact2->uuid,
    ],
    [
        'id' => $posit->recipient->id,
        'uuid' => $posit->recipient->uuid,
    ]);
});

test('proposal cannot get recipient for wrong given access code', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $contact2 = TeamContact::factory()->create(['team_id' => $team->id]);
    $posit->recipients()->sync([$contact->id, $contact2->id]);

    $recipient = $posit->recipientForAccessCode('wrong');
    assertNull($recipient);
});

test('proposal can get recipient for given access code', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);
    $contact2 = TeamContact::factory()->create(['team_id' => $team->id]);
    $posit->recipients()->sync([$contact->id, $contact2->id]);

    $recipient = $posit->recipientForAccessCode($contact->access_code);
    assertInstanceOf(TeamContact::class, $recipient);
    assertEquals($contact->id, $recipient->id);
});

test('proposal creator is the team owner', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    assertInstanceOf(User::class, $posit->creator);
    assertEquals($user->id, $posit->creator->id);
});

test('proposal has a deposit payment', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    $payment = $posit->payments()->create([
        'type' => null
    ]);

    $depositPayment = $posit->payments()->create([
        'type' => PositPayment::TYPE_DEPOSIT
    ]);

    assertInstanceOf(PositPayment::class, $posit->depositPayment);
    assertEquals($depositPayment->id, $posit->depositPayment->id);

});

