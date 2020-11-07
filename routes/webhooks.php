<?php

use CloudCreativity\LaravelStripe\Facades\Stripe;

Stripe::webhook('/stripe/webhooks/connect', 'connect');
