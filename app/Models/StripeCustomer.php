<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StripeCustomer extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the stripe account this customer belongs to.
     *
     * @return BelongsTo The belongs to relationship.
     */
    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class);
    }

    /**
     * Get the owning model.
     *
     * @return MorphTo The morph to relationship.
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
