<?php

use App\Actions\InAppCredit\ApplyCreditsFromPaddlePurchase;
use App\Models\Team;
use App\Utils\Paddle;
use Laravel\Paddle\Receipt;

test('apply credits from paddle product purchase', function () {
    $team = Team::factory()->create();

    Paddle::product(['product_id' => 1])->credits(10);

    $receipt = new Receipt(['product_id' => 1]);

    $teamCreditBalanceBefore = $team->inAppCreditBalance();

    ApplyCreditsFromPaddlePurchase::run($receipt, $team);

    assertEquals($teamCreditBalanceBefore + 10, $team->inAppCreditBalance());
});
