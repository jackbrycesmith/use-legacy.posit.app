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
        'line_items' => 'array',
        'payment_method_types' => 'array',
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
        $this->success_url = data_get($session, 'success_url');
        $this->cancel_url = data_get($session, 'cancel_url');
        $this->amount_subtotal = data_get($session, 'amount_subtotal');
        $this->amount_total = data_get($session, 'amount_total');
        $this->payment_method_types = data_get($session, 'payment_method_types');

        // TODO other things

        return $this;
    }
}
