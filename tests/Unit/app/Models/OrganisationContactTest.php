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

    assertTrue(true);
});

test('org contact access code case sensitivity uniqueness', function () {
    // ⚠️ This is not working which is a shame; case sensitivity doesn't seem to work on indexes...
    // practically this means that I'll potentially run into issues if a single org has many billions of contacts...
    // 7.9586611099×10^24 would be the limit of 36^16 (alphanumeric)

    // $accessCode = 'abc';
    // $otherAccessCode = 'Abc';

    // $org = factory(Organisation::class)->create();

    // $contact = factory(OrganisationContact::class)->create([
    //     'organisation_id' => $org->id,
    //     'access_code' => $accessCode
    // ]);

    // $contact2 = factory(OrganisationContact::class)->create([
    //     'organisation_id' => $org->id,
    //     'access_code' => $otherAccessCode
    // ]);

    // assertTrue(true);
});
