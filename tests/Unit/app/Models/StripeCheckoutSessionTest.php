<?php

use App\Models\StripeCheckoutSession;
use Stripe\Checkout\Session;

it('can fill from api stripe checkout session', function () {
    $apiSession = new Session('cs_test_xxx');
    $session = (new StripeCheckoutSession)->fillFrom($apiSession);
    assertEquals('cs_test_xxx', $session->id);
});
