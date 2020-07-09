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

    $proposal = $org->proposals()->create();
    $orgMemberId = OrganisationMember::first()->id;
    ProposalUser::create(['proposal_id' => $proposal->id, 'organisation_member_id' => $orgMemberId]);

    assertEquals(1, $user->proposals()->count());
    assertInstanceOf(Collection::class, $user->proposals);
    assertInstanceOf(Proposal::class, $user->proposals->first());
    assertEquals($proposal->id, $user->proposals->first()->id);
})->only();

