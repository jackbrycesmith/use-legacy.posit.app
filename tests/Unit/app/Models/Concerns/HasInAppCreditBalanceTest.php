<?php

use App\Models\InAppCredit;
use App\Models\Team;

test('has 0 balance if no transactions', function () {
    $team = Team::factory()->create();

    assertEquals(0, $team->inAppCreditTransactions()->count());
    assertEquals(0, $team->inAppCreditBalance());
});

test('accounts only for balance_model transactions', function () {
    $team = Team::factory()->create();
    $otherTeam = Team::factory()->create();

    InAppCredit::increase(2, $otherTeam);
    InAppCredit::decrease(1, $otherTeam);

    assertEquals(0, $team->inAppCreditTransactions()->count());
    assertEquals(0, $team->inAppCreditBalance());

    InAppCredit::increase(5, $team);
    InAppCredit::decrease(1, $team);
    InAppCredit::increase(2, $team);
    assertEquals(6, $team->inAppCreditBalance());
});
