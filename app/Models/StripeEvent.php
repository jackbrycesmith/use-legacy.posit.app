<?php

namespace App\Models;

use CloudCreativity\LaravelStripe\Models\StripeEvent as LaravelStripeEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StripeEvent extends LaravelStripeEvent
{
    use HasFactory;

}
