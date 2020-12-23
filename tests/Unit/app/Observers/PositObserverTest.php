<?php

use App\Enums\PositType;
use App\Models\Posit;
use App\Models\Team;
use App\Models\Values\PositConfig;
use Illuminate\Support\Arr;

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

test('it sets posit type to default if null when creating', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->make(['team_id' => $team->id]);

    // Sanity check when creating
    expect(Arr::get($posit->getAttributes(), 'type'))->toBeNull();

    $posit->save();
    $posit->refresh();

    expect($posit->type)->toBeInstanceOf(PositType::class);

    $this->assertDatabaseHas('posits', [
        'id' => $posit->id,
        'type' => 'accept_only',
    ]);
});

test('it does not set default posit type if set when creating', function () {
    $team = Team::factory()->create();
    $posit = Posit::factory()->make(['team_id' => $team->id, 'type' => PositType::accept_and_pay()]);

    // Sanity check when creating
    expect(Arr::get($posit->getAttributes(), 'accept_and_pay'))->toBeNull();

    $posit->save();
    $posit->refresh();

    expect($posit->type)->toBeInstanceOf(PositType::class);

    $this->assertDatabaseHas('posits', [
        'id' => $posit->id,
        'type' => 'accept_and_pay',
    ]);
});
