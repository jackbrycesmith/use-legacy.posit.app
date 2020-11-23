<?php

use App\Actions\Integrations\Paddle\HandlePaddlePaymentSucceeded;
use App\Models\Team;
use Laravel\Paddle\Events\PaymentSucceeded;
use Laravel\Paddle\Receipt;

it('listens for event', function () {
    $this->partialMock(HandlePaddlePaymentSucceeded::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    PaymentSucceeded::dispatch(new Team(), new Receipt(), []);
});
