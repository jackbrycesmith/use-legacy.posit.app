<?php

use App\Models\Organisation;
use App\Models\OrganisationContact;
use Illuminate\Database\QueryException;

test('org contact access code must be unique for the org, enforced by db unique index', function () {
    $accessCode = 'abc';

    $org = factory(Organisation::class)->create();

    $contact = factory(OrganisationContact::class)->create([
        'organisation_id' => $org->id,
        'access_code' => $accessCode
    ]);

    // same access code for the org will fail...
    $this->expectException(QueryException::class);
    $contact2 = factory(OrganisationContact::class)->create([
        'organisation_id' => $org->id,
        'access_code' => $accessCode
    ]);
});

test('org contact access code can be same for different orgs, enforced by db unique index', function () {
    $accessCode = 'abc';

    $org = factory(Organisation::class)->create();
    $org2 = factory(Organisation::class)->create();

    $contact = factory(OrganisationContact::class)->create([
        'organisation_id' => $org->id,
        'access_code' => $accessCode
    ]);

    $contact2 = factory(OrganisationContact::class)->create([
        'organisation_id' => $org2->id,
        'access_code' => $accessCode
    ]);

    // Test correct access for $org & $contact2
    $accessCode = $contact->access_code;
    $orgContactQuery1 = $org->contacts()->where(function ($query) use ($accessCode) {
        $query->where('access_code', $accessCode);
    });

    assertEquals(1, $orgContactQuery1->count());
    assertEquals($contact->id, $orgContactQuery1->first()->id);

    // Test correct access for $org2 & $contact2
    $accessCode = $contact2->access_code;
    $orgContactQuery2 = $org2->contacts()->where(function ($query) use ($accessCode) {
        $query->where('access_code', $accessCode);
    });

    assertEquals(1, $orgContactQuery2->count());
    assertEquals($contact2->id, $orgContactQuery2->first()->id);

    assertTrue(true);
});

test('org contact access code case sensitivity uniqueness', function () {
    $accessCode = 'abc';
    $otherAccessCode = 'Abc';

    $org = factory(Organisation::class)->create();

    $contact = factory(OrganisationContact::class)->create([
        'organisation_id' => $org->id,
        'access_code' => $accessCode
    ]);

    $contact2 = factory(OrganisationContact::class)->create([
        'organisation_id' => $org->id,
        'access_code' => $otherAccessCode
    ]);

    assertEquals($contact->id, OrganisationContact::firstWhere('access_code', $accessCode)->id);
    assertEquals($contact2->id, OrganisationContact::firstWhere('access_code', $otherAccessCode)->id);

    assertTrue(true);
});
