<?php

namespace App\Models;

use CloudCreativity\LaravelStripe\Models\StripeAccount as LaravelStripeAccount;

class StripeAccount extends LaravelStripeAccount
{
    protected $dates = [
        'deleted_at',
        'created'
    ];
}
