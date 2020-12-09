<?php

use App\Actions\Team\CreateDraftPosit;
use App\Models\Posit;
use App\Models\States\Posit\PositState;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use function Tests\actingAs;

test('updating posit value requires proposal exist', function () {
    $response = $this->put(route('use.submit.upsert-posit-value', ['posit' => 'blah']));
    $response->assertStatus(404);
});

test('updating posit value requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-posit-value', ['posit' => $posit]));
    $response->assertRedirect(route('login'));
});

test('user cannot update posit value if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-posit-value', ['posit' => $posit]));

    $response->assertStatus(403);
});

test('user cannot update posit value when in certain states', function ($state) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

    Event::fake();

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

test('user cannot update posit value if missing params', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-value', ['posit' => $posit]),
        [
            //
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'value_currency_code' => 'The value currency code field is required.'
    ]);
    $response->assertJsonMissingValidationErrors([
        'value_amount' => 'The value amount field is required.',
    ]);

});

test('user cannot update posit value if not allowed currency code', function ($currencyCode) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-value', ['posit' => $posit]),
        [
            'value_currency_code' => $currencyCode
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'value_currency_code' => 'The selected value currency code is invalid.'
    ]);

})->with([
    'BHD' // e.g. Bahraini Dinar not supported
]);

test('user cannot update posit value if negative amount', function ($amount) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-value', ['posit' => $posit]),
        [
            'value_amount' => $amount,
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'value_amount' => 'The value amount must be greater than or equal 0.'
    ]);

})->with([
    -0.01,
    -1,
]);

test('user cannot update posit value if too many decimal places', function ($amount) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-value', ['posit' => $posit]),
        [
            'value_amount' => $amount,
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'value_amount' => 'The value amount format is invalid.'
    ]);

})->with([
    1.0002,
    1.002,
]);

test('user cannot update posit value if amount too high', function ($amount) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-value', ['posit' => $posit]),
        [
            'value_amount' => $amount,
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'value_amount' => 'The value amount may not be greater than 999999999.99.'
    ]);

})->with([
    1000000000,
]);

test('user can update posit value if team member & valid params', function ($amount, $currencyCode) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-posit-value', ['posit' => $posit]),
        [
            'value_amount' => $amount,
            'value_currency_code' => $currencyCode,
        ]
    );
    $response->assertStatus(204);

    $amount = $amount == '' ? null : $amount; // Empty string gets converted to null
    $this->assertDatabaseHas('posits', [
        'id' => $posit->id,
        'value_amount' => $amount,
        'value_currency_code' => $currencyCode,
    ]);
})->with([
    [
        null, 'GBP'
    ],
    [
        '', 'GBP'
    ],
    [
        1000, 'GBP'
    ],
    [
        "1000", 'GBP'
    ],
    [
        1000.99, 'USD'
    ],
]);


