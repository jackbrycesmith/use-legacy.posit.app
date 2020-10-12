<?php

use App\Actions\Team\CreateDraftProposal;
use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;

test('updating proposal value requires proposal exist', function () {
    $response = $this->put(route('use.submit.upsert-proposal-value', ['proposal' => 'blah']));
    $response->assertStatus(404);
});

test('updating proposal value requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-proposal-value', ['proposal' => $proposal]));
    $response->assertRedirect(route('login'));
});

test('user cannot update proposal value if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-proposal-value', ['proposal' => $proposal]));

    $response->assertStatus(403);
});

test('user cannot update proposal value if missing params', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-value', ['proposal' => $proposal]),
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

test('user cannot update proposal value if not allowed currency code', function ($currencyCode) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-value', ['proposal' => $proposal]),
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

test('user cannot update proposal value if negative amount', function ($amount) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-value', ['proposal' => $proposal]),
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

test('user cannot update proposal value if too many decimal places', function ($amount) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-value', ['proposal' => $proposal]),
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

test('user cannot update proposal value if amount too high', function ($amount) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-value', ['proposal' => $proposal]),
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

test('user can update proposal value if team member & valid params', function ($amount, $currencyCode) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-value', ['proposal' => $proposal]),
        [
            'value_amount' => $amount,
            'value_currency_code' => $currencyCode,
        ]
    );
    $response->assertStatus(204);

    $this->assertDatabaseHas('proposals', [
        'id' => $proposal->id,
        'value_amount' => $amount,
        'value_currency_code' => $currencyCode,
    ]);
})->with([
    [
        null, 'GBP'
    ],
    [
        1000, 'GBP'
    ],
    [
        1000.99, 'USD'
    ],
]);


