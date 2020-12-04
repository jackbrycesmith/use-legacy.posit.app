<?php

use App\Models\Posit;
use App\Models\Team;

test('it sets posit status to draft by default when created', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    assertEquals(Posit::STATUS_DRAFT, $posit->status);
    $this->assertDatabaseHas('statuses', [
        'name' => Posit::STATUS_DRAFT,
        'model_id' => $posit->id,
        'model_type' => 'posit'
    ]);
});

test('it sets posit theme to default if null when creating', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->make(['team_id' => $team->id]);

    assertNull($posit->theme);

    $posit->save();
    assertNotNull($posit->theme);
    assertContains($posit->theme, Posit::ALLOWED_THEMES);
});
