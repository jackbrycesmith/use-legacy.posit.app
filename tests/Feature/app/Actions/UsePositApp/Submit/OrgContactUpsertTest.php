<?php

use App\Models\Organisation;
use App\Models\OrganisationContact;
use App\Models\User;
use Illuminate\Support\Arr;
use function Tests\actingAs;

test('user cannot update the contact if it does not belong to the org', function () {
    $user = factory(User::class)->create();
    $org = $user->organisations->first();

    $otherOrg = factory(Organisation::class)->create();
    $otherContact = factory(OrganisationContact::class)->create(['organisation_id' => $otherOrg->id]);

    $response = actingAs($user)->put(
        route('use.org.contacts.update', ['org' => $org, 'contact' => $otherContact->id]),
        [
            'name' => 'New name...'
        ]
    );

    $response->assertStatus(403);
    assertNotEquals('New name...', Arr::get($otherContact->refresh()->meta, 'name'));
});
