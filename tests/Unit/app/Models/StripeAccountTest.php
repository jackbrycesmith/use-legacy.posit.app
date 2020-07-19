<?php

use App\Models\Organisation;
use App\Models\StripeAccount;
use App\Models\User;

test('it has an organisation', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations()->create(['name' => 'org']);

    $stripeAccount = factory(StripeAccount::class)->create(['owner_id' => $org->id]);

    assertInstanceOf(Organisation::class, $stripeAccount->owner);
    assertEquals($org->id, $stripeAccount->owner->id);
});
