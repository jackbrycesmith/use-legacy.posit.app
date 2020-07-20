<?php

use App\Models\Organisation;
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
    '/org/some-uuid/stripe-connect-oauth-start',
]);

test('macro Request::stripeConnectOauthOrg returns organisation', function () {
    $org = factory(Organisation::class)->create();

    $stateQuery = "sessiontoken.{$org->uuid}";
    $request = Request::create('/', 'GET', ['state' => $stateQuery]);

    assertInstanceOf(Organisation::class, $request->stripeConnectOauthOrg());
});

test('macro Request::stripeConnectOauthOrg returns null organisation', function ($stateQuery) {
    $org = factory(Organisation::class)->create();
    $request = Request::create('/', 'GET', ['state' => $stateQuery]);

    assertNull($request->stripeConnectOauthOrg());
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
