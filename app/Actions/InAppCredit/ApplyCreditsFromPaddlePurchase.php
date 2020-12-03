<?php

namespace App\Actions\InAppCredit;

use App\Models\InAppCredit;
use App\Utils\Paddle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
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

        InAppCredit::increase($creditAmount, $this->billable, $this->receipt);

        // TODO broadcast to frontend that this is done
        // TODO notify user via email saying successful etc.
    }
}
