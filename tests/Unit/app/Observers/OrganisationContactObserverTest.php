<?php

use App\Models\Organisation;
use App\Models\OrganisationContact;

test('it sets org contact access code on creation', function () {
    $org = factory(Organisation::class)->create();
    $contact = factory(OrganisationContact::class)->create(['organisation_id' => $org->id]);

    assertNotNull($contact->access_code);
    assertEquals(config('posit-settings.org_contact_access_code_length', 16), strlen($contact->access_code));
});
