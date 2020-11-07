<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Stripe\Checkout\Session;

class StripeCheckoutSession extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount_subtotal' => 'integer',
        'amount_total' => 'integer',
        'payment_method_types' => 'array',
        'livemode' => 'boolean',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the stripe account this checkout session belongs to.
     *
     * @return BelongsTo The belongs to relationship.
     */
    public function stripeAccount(): BelongsTo
    {
        return $this->belongsTo(StripeAccount::class);
    }

    /**
     * Get the stripe customer this checkout session belongs to.
     *
     * @return BelongsTo The belongs to relationship.
     */
    public function stripeCustomer(): BelongsTo
    {
        return $this->belongsTo(StripeCustomer::class);
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
     * Fill attributes from stripe api checkout response
     *
     * @param \Stripe\Checkout\Session $session The api session
     *
     * @return self
     */
    public function fillFrom(Session $session)
    {
        $this->id = data_get($session, 'id');
        $this->currency = data_get($session, 'currency');
        $this->mode = data_get($session, 'mode');
        $this->livemode = (bool) data_get($session, 'livemode');
        $this->payment_status = data_get($session, 'payment_status');
        $this->amount_subtotal = data_get($session, 'amount_subtotal');
        $this->amount_total = data_get($session, 'amount_total');
        $this->payment_method_types = data_get($session, 'payment_method_types');

        if (($customer = data_get($session, 'customer')) && is_string($customer)) {
            $this->stripe_customer_id = $customer;
        }

        if (($paymentIntent = data_get($session, 'payment_intent')) && is_string($paymentIntent)) {
            $this->stripe_payment_intent_id = $paymentIntent;
        }

        return $this;
    }
}
