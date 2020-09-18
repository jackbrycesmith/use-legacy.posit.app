<?php

use App\Models\Proposal;
use App\Models\Team;

test('it sets proposal status to draft by default when created', function () {
    $team = Team::factory()->create();
    $proposal = Proposal::factory()->create(['team_id' => $team->id]);

    assertEquals(Proposal::STATUS_DRAFT, $proposal->status);
    $this->assertDatabaseHas('statuses', [
        'name' => Proposal::STATUS_DRAFT,
        'model_id' => $proposal->id,
        'model_type' => 'proposal'
    ]);
});
