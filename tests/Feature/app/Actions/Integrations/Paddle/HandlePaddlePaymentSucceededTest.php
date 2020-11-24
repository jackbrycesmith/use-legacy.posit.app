<?php

use App\Actions\InAppCredit\ApplyCreditsFromPaddlePurchase;
use App\Actions\Integrations\Paddle\HandlePaddlePaymentSucceeded;
use App\Models\Team;
use Illuminate\Support\Facades\Bus;
use Laravel\Paddle\Events\PaymentSucceeded;
use Laravel\Paddle\Receipt;

it('listens for event', function () {
    $this->partialMock(HandlePaddlePaymentSucceeded::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    PaymentSucceeded::dispatch(new Team(), new Receipt(), []);
});

it('dispatches job to apply in app credits', function () {
    Bus::fake();

    PaymentSucceeded::dispatch(new Team(), new Receipt(), []);

    Bus::assertDispatched(ApplyCreditsFromPaddlePurchase::class);
});
