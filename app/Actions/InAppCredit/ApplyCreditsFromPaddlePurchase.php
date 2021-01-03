<?php

namespace App\Actions\InAppCredit;

use App\Models\InAppCredit;
use App\Models\Team;
use App\Notifications\Team\TeamCreditsAppliedFromPaddlePurchase;
use App\Utils\Paddle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Paddle\Receipt;
use Lorisleiva\Actions\Action;

class ApplyCreditsFromPaddlePurchase extends Action implements ShouldQueue
{
    public function getAttributesFromConstructor(Receipt $receipt, Model $billable)
    {
        return compact('receipt', 'billable');
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        $creditAmount = Paddle::creditsForProduct((int) $this->receipt->product_id);
        $billable = $this->billable;
        $receipt = $this->receipt;

        DB::transaction(function () use ($creditAmount, $billable, $receipt) {
            InAppCredit::increase($creditAmount, $billable, $receipt);
        });

        // TODO broadcast to frontend that this is done

        if (is_a($billable, Team::class)) {
            $billable->notify(new TeamCreditsAppliedFromPaddlePurchase($creditAmount));
        }

        telegram_me_now("💸 {$creditAmount} credits applied from paddle purchase");
    }
}
