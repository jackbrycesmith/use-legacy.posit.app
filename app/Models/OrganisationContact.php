<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class OrganisationContact extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'organisation_id' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Get the organisation that this contact belongs to
     *
     * @return BelongsTo The belongs to relationship.
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the stripe customer.
     *
     * @return MorphOne The morph one relationship.
     */
    public function stripeCustomer(): MorphOne
    {
        return $this->morphOne(StripeCustomer::class, 'model');
    }

}
