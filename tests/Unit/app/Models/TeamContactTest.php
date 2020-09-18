<?php

use App\Models\Team;
use App\Models\TeamContact;
use Illuminate\Database\QueryException;

test('team contact access code must be unique for the team, enforced by db unique index', function () {
    $accessCode = 'abc';

    $team = Team::factory()->create();

    TeamContact::factory()->create([
        'team_id' => $team->id,
        'access_code' => $accessCode
    ]);

    // same access code for the team will fail...
    $this->expectException(QueryException::class);
    TeamContact::factory()->create([
        'team_id' => $team->id,
        'access_code' => $accessCode
    ]);
});

test('team contact access code can be same for different teams, enforced by db unique index', function () {
    $accessCode = 'abc';

    $team = Team::factory()->create();
    $team2 = Team::factory()->create();

    $contact = TeamContact::factory()->create([
        'team_id' => $team->id,
        'access_code' => $accessCode
    ]);

    $contact2 = TeamContact::factory()->create([
        'team_id' => $team2->id,
        'access_code' => $accessCode
    ]);

    // Test correct access for $team & $contact2
    $accessCode = $contact->access_code;
    $teamContactQuery1 = $team->contacts()->where(function ($query) use ($accessCode) {
        $query->where('access_code', $accessCode);
    });

    assertEquals(1, $teamContactQuery1->count());
    assertEquals($contact->id, $teamContactQuery1->first()->id);

    // Test correct access for $team2 & $contact2
    $accessCode = $contact2->access_code;
    $teamContactQuery2 = $team2->contacts()->where(function ($query) use ($accessCode) {
        $query->where('access_code', $accessCode);
    });

    assertEquals(1, $teamContactQuery2->count());
    assertEquals($contact2->id, $teamContactQuery2->first()->id);

    assertTrue(true);
});

test('team contact access code case sensitivity uniqueness', function () {
    $accessCode = 'abc';
    $otherAccessCode = 'Abc';

    $team = Team::factory()->create();

    $contact = TeamContact::factory()->create([
        'team_id' => $team->id,
        'access_code' => $accessCode
    ]);

    $contact2 = TeamContact::factory()->create([
        'team_id' => $team->id,
        'access_code' => $otherAccessCode
    ]);

    assertEquals($contact->id, TeamContact::firstWhere('access_code', $accessCode)->id);
    assertEquals($contact2->id, TeamContact::firstWhere('access_code', $otherAccessCode)->id);

    assertTrue(true);
});
