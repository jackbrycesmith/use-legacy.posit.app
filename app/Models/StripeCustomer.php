<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stripe\Customer;

class StripeCustomer extends Model
{
    use HasFactory;
    use SoftDeletes;

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
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'created' => 'datetime',
    ];

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

    /**
     * Fill the model attributes from the Stripe Customer API object
     *
     * @param \Stripe\Customer $stripeCustomer The stripe customer
     *
     * @throws \InvalidArgumentException if account id mismatch
     *
     * @return self
     */
    public function fillFrom(Customer $stripeCustomer)
    {
        $customerId = data_get($stripeCustomer, 'id');

        if (!is_null($this->id) && $this->id !== $customerId) {
            throw new \InvalidArgumentException("Stripe customer id update mismatch: current: {$this->id}, update: {$customerId}");
        }

        $this->id = data_get($stripeCustomer, 'id');
        $this->name = data_get($stripeCustomer, 'name');
        $this->email = data_get($stripeCustomer, 'email');
        $this->phone = data_get($stripeCustomer, 'phone');
        $this->created = data_get($stripeCustomer, 'created');
        $this->livemode = (bool) data_get($stripeCustomer, 'livemode');
        $this->currency = data_get($stripeCustomer, 'currency');

        return $this;
    }
}
