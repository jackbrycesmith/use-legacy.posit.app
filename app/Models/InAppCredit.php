<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class InAppCredit extends Model
{
    use HasUuid;
    use HasFactory;

    const TYPE_INCREASE = 'increase';
    const TYPE_DECREASE = 'decrease';

    /**
     * Get the owning balance model (e.g. the team).
     *
     * @return MorphTo The morph to relation.
     */
    public function balanceModel(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the owning usage model (i.e. what these credits were used on).
     *
     * @return MorphTo The morph to relation.
     */
    public function usageModel(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the owning initiator model (e.g. which team member).
     *
     * @return MorphTo The morph to relation.
     */
    public function initiatorModel(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Increase the credit balance for a given owner
     *
     * @param integer $amount
     * @param \Illuminate\Database\Eloquent\Model $balanceModel
     * @param \Illuminate\Database\Eloquent\Model|null $usageModel
     * @param \Illuminate\Database\Eloquent\Model|null $initiatorModel
     *
     * @throws Exception (description)
     *
     * @return InAppCredit In application credit.
     */
    public static function increase(int $amount, Model $balanceModel, ?Model $usageModel = null, ?Model $initiatorModel = null)
    {
        $inAppCredit = new InAppCredit();
        $inAppCredit->type = InAppCredit::TYPE_INCREASE;
        $inAppCredit->amount = $amount;
        $inAppCredit->balanceModel()->associate($balanceModel);

        if ($usageModel) {
            $inAppCredit->usageModel()->associate($usageModel);
        }

        if ($initiatorModel) {
            $inAppCredit->initiatorModel()->associate($initiatorModel);
        }

        $success = $inAppCredit->save();
        if (! $success) {
            throw new Exception('Failed to save increase in app credit transaction.');
        }

        return $inAppCredit;
    }


    /**
     * Decrease the credit balance for a given owner
     *
     * @param integer $amount
     * @param \Illuminate\Database\Eloquent\Model $balanceModel
     * @param \Illuminate\Database\Eloquent\Model|null $usageModel
     * @param \Illuminate\Database\Eloquent\Model|null $initiatorModel
     *
     * @throws Exception (description)
     *
     * @return InAppCredit In application credit.
     */
    public static function decrease(int $amount, Model $balanceModel, ?Model $usageModel = null, ?Model $initiatorModel = null)
    {
        $inAppCredit = new InAppCredit();
        $inAppCredit->type = InAppCredit::TYPE_DECREASE;
        $inAppCredit->amount = $amount;
        $inAppCredit->balanceModel()->associate($balanceModel);

        if ($usageModel) {
            $inAppCredit->usageModel()->associate($usageModel);
        }

        if ($initiatorModel) {
            $inAppCredit->initiatorModel()->associate($initiatorModel);
        }

        DB::transaction(function () use ($inAppCredit, $balanceModel) {
            $success = $inAppCredit->save();
            if (! $success) {
                throw new Exception('Failed to save decrease in app credit transaction.');
            }

            $balance = $balanceModel->inAppCreditBalance();

            if ($balance < 0) {
                throw new Exception('Transaction would make negative balance');
            }
        });

        if (! $inAppCredit->exists()) {
            throw new Exception('Failed to save decrease in app credit transaction.');
        }

        return $inAppCredit;
    }
}
