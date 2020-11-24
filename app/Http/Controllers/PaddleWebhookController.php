<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Laravel\Paddle\Events\PaymentSucceeded;
use Laravel\Paddle\Http\Controllers\WebhookController as CashierPaddleController;

class PaddleWebhookController extends CashierPaddleController
{
    /**
     * Handle one-time payment succeeded.
     *
     * @param  array  $payload
     * @return void
     */
    protected function handlePaymentSucceeded(array $payload)
    {
        if ($this->receiptExists($payload['order_id'])) {
            return;
        }

        $customer = $this->findOrCreateCustomer($payload['passthrough']);

        $receipt = $customer->receipts()->create([
            'checkout_id' => $payload['checkout_id'],
            'order_id' => $payload['order_id'],
            'product_id' => Arr::get($payload, 'product_id'), // TODO store as integer?
            'amount' => $payload['sale_gross'],
            'tax' => $payload['payment_tax'],
            'currency' => $payload['currency'],
            'quantity' => (int) $payload['quantity'],
            'receipt_url' => $payload['receipt_url'],
            'paid_at' => Carbon::createFromFormat('Y-m-d H:i:s', $payload['event_time'], 'UTC'),
        ]);

        PaymentSucceeded::dispatch($customer, $receipt, $payload);
    }
}
