<?php

use App\Models\Team;
use Illuminate\Http\Request;

test('macro Request::isStripeConnectOauthStart returns false', function ($urlPath) {
    $request = Request::create($urlPath, 'GET', []);

    assertFalse($request->isStripeConnectOauthStart());
})->with([
    '/',
    '/stripe-connect-oauth-start',
]);

test('macro Request::isStripeConnectOauthStart returns true', function ($urlPath) {
    $request = Request::create($urlPath, 'GET', []);

    assertTrue($request->isStripeConnectOauthStart());
})->with([
    '/team/some-uuid/stripe-connect-oauth-start',
]);

test('macro Request::stripeConnectOauthTeam returns team', function () {
    $org = Team::factory()->create();

    $stateQuery = "sessiontoken.{$org->uuid}";
    $request = Request::create('/', 'GET', ['state' => $stateQuery]);

    assertInstanceOf(Team::class, $request->stripeConnectOauthTeam());
});

test('macro Request::stripeConnectOauthTeam returns null team', function ($stateQuery) {
    $org = Team::factory()->create();
    $request = Request::create('/', 'GET', ['state' => $stateQuery]);

    assertNull($request->stripeConnectOauthTeam());
})->with([
    null,
    'onlytoken',
    'token.invalid-uuid'
]);

test('macro Request::stripeConnectOauthStateSessionToken returns session token', function () {
    $request = Request::create('/', 'GET', ['state' => 'token.uuid']);

    assertEquals('token', $request->stripeConnectOauthStateSessionToken());
});

test('macro Request::stripeConnectOauthStateSessionToken returns null session token', function ($stateQuery) {
    $request = Request::create('/', 'GET', ['state' => $stateQuery]);
    assertNull($request->stripeConnectOauthStateSessionToken());
})->with([
    null,
]);
