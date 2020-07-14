<?php

use App\Models\User;

it('creates personal organisation after user creation', function () {
    $user = factory(User::class)->create();
    assertEquals(1, $user->organisations()->count());
    assertEquals($user->email, $user->organisations->first()->name);
});
