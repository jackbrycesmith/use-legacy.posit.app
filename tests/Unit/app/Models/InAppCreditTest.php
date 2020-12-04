<?php

use App\Models\InAppCredit;
use App\Models\Posit;
use App\Models\Team;
use App\Models\User;

test('increase in app credit balance', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    $inAppCredit = InAppCredit::increase(5, $team, $posit, $user);
    assertInstanceOf(InAppCredit::class, $inAppCredit);

    $this->assertDatabaseHas('in_app_credits', [
        'id' => $inAppCredit->id,
        'balance_model_type' => 'team',
        'balance_model_id' => $team->id,
        'amount' => 5,
        'usage_model_type' => 'posit',
        'usage_model_id' => $posit->id,
        'initiator_model_type' => 'user',
        'initiator_model_id' => $user->id
    ]);
});

test('decrease in app credit balance roll back if negative balance', function () {
    $team = Team::factory()->create();

    $inAppCreditEntryCountBefore = InAppCredit::count();

    $this->expectException(\Exception::class);
    InAppCredit::decrease(1, $team);

    assertEquals($inAppCreditEntryCountBefore, InAppCredit::count());
    assertEquals(0, $team->inAppCreditBalance());
});
