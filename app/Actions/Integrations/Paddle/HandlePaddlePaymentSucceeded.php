<?php

namespace App\Actions\Integrations\Paddle;

use Laravel\Paddle\Events\PaymentSucceeded;
use Lorisleiva\Actions\Action;

class HandlePaddlePaymentSucceeded extends Action
{
    public function getAttributesFromEvent(PaymentSucceeded $event)
    {
        return [
            'billable' => $event->billable,
            'receipt' => $event->receipt,
            'payload' => $event->payload,
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        // TODO increase the amount of credits for a given user.
    }
}
