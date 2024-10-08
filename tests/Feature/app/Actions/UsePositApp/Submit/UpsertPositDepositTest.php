<?php

use App\Actions\Team\CreateDraftPosit;
use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\States\Posit\PositState;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use function Tests\actingAs;

test('updating posit deposit requires posit exist', function () {
    $response = $this->put(route('use.submit.upsert-posit-deposit', ['posit' => 'blah']));
    $response->assertStatus(404);
});

test('updating posit deposit requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-posit-deposit', ['posit' => $posit]));
    $response->assertRedirect(route('login'));
});

test('user cannot update posit deposit if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-posit-deposit', ['posit' => $posit]));

    $response->assertStatus(403);
});

test('user cannot upsert posit deposit in certain states', function ($state) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

    assertNull($posit->depositPayment);

    Event::fake();

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-deposit', ['posit' => $posit]),
        [
            //
        ]
    );

    $posit->refresh();
    $response->assertStatus(403);
    assertNull($posit->depositPayment);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-value', ['posit' => $posit]),
        [
            'value_amount' => 1,
            'value_currency_code' => 'GBP',
        ]
    );

    $response->assertStatus(403);
})->with(
    PositState::all()->except(PositState::statesThatCanUpdateThePosit())->keys()
);

test('it creates posit deposit payment if non-existant', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $posit->refresh();
    assertNull($posit->depositPayment);
    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-deposit', ['posit' => $posit]),
        [
            //
        ]
    );

    $posit->refresh();
    $response->assertStatus(204);
    assertNotNull($posit->depositPayment);
});

test('user can update posit deposit payment amount', function ($amount) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $positDeposit = $posit->depositPayment()->firstOrCreate([
        'type' => PositPayment::TYPE_DEPOSIT
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-deposit', ['posit' => $posit]),
        [
            'amount' => $amount
        ]
    );

    $posit->refresh();
    assertEquals($positDeposit->id, $posit->depositPayment->id);
    $response->assertStatus(204);

    $amount = $amount == '' ? null : $amount; // Empty string gets converted to null
    $this->assertDatabaseHas('posit_payments', [
        'id' => $positDeposit->id,
        'posit_id' => $posit->id,
        'type' => 'deposit',
        'amount' => $amount,
    ]);
})->with([
    [
        null
    ],
    [
        ''
    ],
    [
        1000
    ],
    [
        "1000"
    ],
    [
        1000.99,
    ],
]);
