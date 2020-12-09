<?php

use App\Models\Posit;
use App\Models\Team;

test('it sets posit theme to default if null when creating', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->make(['team_id' => $team->id]);

    assertNull($posit->theme);

    $posit->save();
    assertNotNull($posit->theme);
    assertContains($posit->theme, Posit::ALLOWED_THEMES);
});
