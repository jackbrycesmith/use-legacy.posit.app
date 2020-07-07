<?php

use App\Models\Organisation;
use App\Models\OrganisationMember;
use App\Models\User;

test('user can create organisation', function () {
    $user = factory(User::class)->create();

    assertEquals(0, $user->organisations()->count());
    $organisation = $user->organisations()->create(['name' => 'org']);
    assertInstanceOf(Organisation::class, $organisation);
    assertEquals(1, $user->organisations()->count());

    // Check pivot timestamps
    $orgMember = OrganisationMember::where('user_id', $user->id)->first();
    assertNotNull($orgMember->created_at);
    assertNotNull($orgMember->updated_at);
});

test('user can get proposals', function () {
    $user = factory(User::class)->create();

    // TODO
});

