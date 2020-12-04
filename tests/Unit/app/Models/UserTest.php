<?php

use App\Models\OrganisationMember;
use App\Models\Posit;
use App\Models\PositUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

test('user can create organisation', function () {
    $user = User::factory()->create();

    $orgCountBefore = $user->organisations()->count();
    $org = $user->organisations()->create(['name' => 'org']);
    assertInstanceOf(Team::class, $org);
    assertEquals($orgCountBefore + 1, $user->organisations()->count());

    // Check pivot timestamps
    $orgMember = OrganisationMember::where('user_id', $user->id)->first();
    assertNotNull($orgMember->created_at);
    assertNotNull($orgMember->updated_at);
})->skip();

test('user can get all accessible proposals across organisations they are members of', function () {
    $user = User::factory()->create();
    assertEquals(0, $user->posits()->count());

    $org = $user->organisations()->create(['name' => 'org']);
    $inaccessibleProposal = $org->proposals()->create(); // i.e. Not a 'ProposalUser'

    $posit1 = $org->proposals()->create();
    $posit2 = $org->proposals()->create();
    $orgMemberId = OrganisationMember::first()->id;
    PositUser::create(['posit_id' => $posit1->id, 'organisation_member_id' => $orgMemberId]);
    PositUser::create(['posit_id' => $posit2->id, 'organisation_member_id' => $orgMemberId]);

    assertEquals(2, $user->posits()->count());
    assertInstanceOf(Collection::class, $user->proposals);
    assertInstanceOf(Posit::class, $user->proposals->first());

    // Double check that they are the correct proposals
    $positIds = $user->proposals->pluck('id')->toArray();
    assertNotContains($inaccessibleProposal->id, $positIds);
    assertContains($posit1->id, $positIds);
    assertContains($posit2->id, $positIds);
})->skip();

