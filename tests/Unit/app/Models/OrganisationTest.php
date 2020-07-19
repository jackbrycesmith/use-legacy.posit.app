<?php

use App\Models\StripeAccount;
use App\Models\User;

it('can get users', function () {
    [$user1, $user2] = factory(User::class, 2)->create();

    $org1 = $user1->organisations()->create(['name' => 'org']);
    $org2 = $user2->organisations()->create(['name' => 'org']);
    assertEquals(1, $org1->users()->count());
    assertEquals(1, $org2->users()->count());

    $orgUser1 = $org1->users->first();
    assertInstanceOf(User::class, $orgUser1);
    assertEquals($user1->id, $orgUser1->id);

    $orgUser2 = $org2->users->first();
    assertInstanceOf(User::class, $orgUser2);
    assertEquals($user2->id, $orgUser2->id);
});

it('has a stripe account', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']);

    $stripeAccount = factory(StripeAccount::class)->create(['owner_id' => $org->id]);

    assertEquals(1, $org->stripeAccounts()->count());
    assertEquals($stripeAccount->id, $org->stripeAccount->id);
});
