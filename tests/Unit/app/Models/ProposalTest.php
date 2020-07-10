<?php

use App\Models\OrganisationMember;
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
