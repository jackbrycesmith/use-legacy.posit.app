<?php

use App\Models\User;

it('creates personal organisation after user creation, with derived name from user email', function ($email, $expected) {
    $user = factory(User::class)->create(['email' => $email]);
    assertEquals(1, $user->organisations()->count());
    assertEquals($expected, $user->organisations->first()->name);
})->with([
    ['test@email.com', 'test'],
]);
