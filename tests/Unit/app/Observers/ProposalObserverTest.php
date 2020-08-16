<?php

use App\Models\Organisation;
use App\Models\Proposal;

test('it sets proposal status to draft by default when created', function () {
    $org = factory(Organisation::class)->create();
    $proposal = factory(Proposal::class)->create(['organisation_id' => $org->id]);

    assertEquals(Proposal::STATUS_DRAFT, $proposal->status);
    $this->assertDatabaseHas('statuses', [
        'name' => Proposal::STATUS_DRAFT,
        'model_id' => $proposal->id,
        'model_type' => 'proposal'
    ]);
});
