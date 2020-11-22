<?php

namespace App\Models\Concerns;

use App\Models\InAppCredit;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

trait HasInAppCreditBalance
{
    /**
     * Get the stripe checkout session that this model owns.
     *
     * @return MorphOne The morph one.
     */
    public function inAppCreditTransactions(): MorphOneOrMany
    {
        return $this->morphMany(InAppCredit::class, 'balance_model');
    }

    /**
     * Get the balance amount for this model.
     *
     * @todo consider doing this as a SQL view & model relationship
     *
     * @return integer
     */
    public function inAppCreditBalance(): int
    {
        $lookup = $this->inAppCreditTransactions()->toBase()
            // Apparrently this filter thing is postgres only
            ->selectRaw("sum(amount) filter (where type = 'increase') as increase_totals")
            ->selectRaw("sum(amount) filter (where type = 'decrease') as decrease_totals")
            ->first();

        return $lookup->increase_totals - $lookup->decrease_totals;
    }
}
