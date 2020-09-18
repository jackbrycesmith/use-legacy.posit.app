<?php

use App\Models\Team;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Spatie\Regex\Regex;

Request::macro('isStripeConnectOauthStart', function () {
    $startsCorrectly = $this->segment(1) === 'org';
    $endsCorrectly = $this->segment(3) === 'stripe-connect-oauth-start';
    return $startsCorrectly && $endsCorrectly;
});

Request::macro('stripeConnectOauthOrg', function () {
    $stripeConnectOauthReturnState = $this->query('state');
    if (is_null($stripeConnectOauthReturnState)) return null;

    // org uuid is placed after session token; App/Utils/StripeOauthSessionState:43
    $orgUuid = Regex::match('/[^.]+$/', $stripeConnectOauthReturnState)->result();
    if (! Uuid::isValid($orgUuid)) return null;

    return Team::where('uuid', $orgUuid)->first();
});

Request::macro('stripeConnectOauthStateSessionToken', function () {
    $state = $this->query('state');
    if (is_null($state)) return null;

    // session token is at start of state; App/Utils/StripeOauthSessionState:43
    return Regex::match('/^(?:[^\.]*)/', $state)->result();
});
