<?php

use App\Models\User;

it('does not auto derive user name from email if already set', function () {
    $user = User::create([
        'name' => 'jack',
        'email' => 'example@email.com',
        'password' => 'password'
    ]);

    assertEquals('jack', $user->name);
});

it('assigns derived name from email address if empty on creating', function ($email, $expected) {
    $user = User::create([
        'email' => $email,
        'password' => 'password'
    ]);

    assertEquals($expected, $user->name);
})->with([
    ['test@email.com', 'test'],
]);

it('creates personal organisation after user creation', function () {
    $user = factory(User::class)->create();
    assertEquals(1, $user->organisations()->count());
    assertEquals($user->email, $user->organisations->first()->name);
});
