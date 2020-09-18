<?php

use App\Models\Team;
use App\Models\TeamContact;

test('it sets team contact access code on creation', function () {
    $team = Team::factory()->create();
    $contact = TeamContact::factory()->create(['team_id' => $team->id]);

    assertNotNull($contact->access_code);
    assertEquals(config('posit-settings.org_contact_access_code_length', 16), strlen($contact->access_code));
})->only();
