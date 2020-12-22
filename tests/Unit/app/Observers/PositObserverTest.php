<?php

use App\Models\Posit;
use App\Models\Team;
use App\Models\Values\PositConfig;

test('it sets posit config to default if null when creating', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->make(['team_id' => $team->id]);

    assertNull($posit->config);

    $posit->save();
    $posit->refresh();

    expect($posit->config)->toBeInstanceOf(PositConfig::class);

    $config = PositConfig::defaults()->toArray();
    $configDatabaseChecks = array_map(fn($val, $key) => ["config->{$key}" => $val], $config, array_keys($config));
    $this->assertDatabaseHas('posits', array_merge([
        'id' => $posit->id,
    ], $configDatabaseChecks[0]));
});
