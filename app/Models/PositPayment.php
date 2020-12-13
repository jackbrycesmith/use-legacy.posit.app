<?php

namespace App\Models;

use App\Models\Concerns\HasStripeCheckoutSession;
use App\Models\Concerns\HasUuid;
use App\Models\States\PositPayment\PositPaymentState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\ModelStates\HasStates;

class PositPayment extends Model
{
    use HasUuid;
    use HasFactory;
    use HasStates;
    use HasStripeCheckoutSession;

    const TYPE_DEPOSIT = 'deposit';
    const PROVIDER_STRIPE = 'stripe';

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
        'amount' => 'float',
        'state' => PositPaymentState::class,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'provider' => self::PROVIDER_STRIPE
    ];

    /**
     * Get the posit
     *
     * @return BelongsTo The belongs to relation.
     */
    public function posit(): BelongsTo
    {
        return $this->belongsTo(Posit::class);
    }

    /**
     * Scope a query to only include deposit type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeposit($query)
    {
        return $query->where('type', '=', self::TYPE_DEPOSIT);
    }

    /**
     * Gets the stripe api amount attribute.
     *
     * @see https://stripe.com/docs/currencies#zero-decimal
     * @todo handle zero decimal currencies e.g. JPY
     * @return integer The stripe api amount attribute.
     */
    public function getStripeApiAmountAttribute(): int
    {
        return (int) $this->amount * 100;
    }
}
