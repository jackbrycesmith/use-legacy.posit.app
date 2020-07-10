<?php

use App\Models\Organisation;
use App\Models\OrganisationMember;
use App\Models\Proposal;
use App\Models\ProposalUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

test('user can create organisation', function () {
    $user = factory(User::class)->create();

    assertEquals(0, $user->organisations()->count());
    $org = $user->organisations()->create(['name' => 'org']);
    assertInstanceOf(Organisation::class, $org);
    assertEquals(1, $user->organisations()->count());

    // Check pivot timestamps
    $orgMember = OrganisationMember::where('user_id', $user->id)->first();
    assertNotNull($orgMember->created_at);
    assertNotNull($orgMember->updated_at);
});

test('user can get all accessible proposals across organisations they are members of', function () {
    $user = factory(User::class)->create();
    assertEquals(0, $user->proposals()->count());

    $org = $user->organisations()->create(['name' => 'org']);
    $inaccessibleProposal = $org->proposals()->create(); // i.e. Not a 'ProposalUser'

    $proposal1 = $org->proposals()->create();
    $proposal2 = $org->proposals()->create();
    $orgMemberId = OrganisationMember::first()->id;
    ProposalUser::create(['proposal_id' => $proposal1->id, 'organisation_member_id' => $orgMemberId]);
    ProposalUser::create(['proposal_id' => $proposal2->id, 'organisation_member_id' => $orgMemberId]);

    assertEquals(2, $user->proposals()->count());
    assertInstanceOf(Collection::class, $user->proposals);
    assertInstanceOf(Proposal::class, $user->proposals->first());

    // Double check that they are the correct proposals
    $proposalIds = $user->proposals->pluck('id')->toArray();
    assertNotContains($inaccessibleProposal->id, $proposalIds);
    assertContains($proposal1->id, $proposalIds);
    assertContains($proposal2->id, $proposalIds);
});

