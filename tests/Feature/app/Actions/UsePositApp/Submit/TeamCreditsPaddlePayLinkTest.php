<?php

use App\Models\Team;
use App\Models\User;
use App\Utils\Paddle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use function Tests\actingAs;

it('404s if team is not in a valid uuid format', function ($teamUuid) {
    Http::fake();

    $response = $this->putJson(route('use.team.credits-paddle-pay-link', [
        'team' => $teamUuid
    ]));

    Http::assertNothingSent();

    $response->assertStatus(404);
})->with([
    'blah',
]);

it('401s if valid uuid, but not logged in', function ($teamUuid) {
    Http::fake();

    $response = $this->putJson(route('use.team.credits-paddle-pay-link', [
        'team' => $teamUuid
    ]));

    Http::assertNothingSent();

    $response->assertStatus(401);
})->with([
    Str::uuid()->toString(),
]);

it('403s if user is not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $otherUser = User::factory()->create();

    Http::fake();

    $response = actingAs($otherUser)->putJson(route('use.team.credits-paddle-pay-link', [
        'team' => $team
    ]));

    Http::assertNothingSent();

    $response->assertStatus(403);

});

it('422s if missing parameters', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    Http::fake();

    $response = actingAs($user)->putJson(route('use.team.credits-paddle-pay-link', [
        'team' => $team
    ]));

    Http::assertNothingSent();

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'product_id' => 'The product id field is required.'
    ]);

});

it('422s if not a valid paddle product_id', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);

    Http::fake();

    $response = actingAs($user)->putJson(
        route('use.team.credits-paddle-pay-link', ['team' => $team]),
        [
            'product_id' => 12345
        ]
    );

    Http::assertNothingSent();

    $response->assertStatus(422);
    $response->assertJsonValidationErrors([
        'product_id' => 'The selected product id is invalid.'
    ]);

});

it('returns pay link if valid paddle product_id', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    Paddle::product(['product_id' => 123]);

    Http::fake([
        '*' => Http::response([
            'success' => true,
            'response' => [
                'url' => 'paddle-checkout-link'
            ]
        ], 200, ['Headers']),
    ]);

    $response = actingAs($user)->putJson(
        route('use.team.credits-paddle-pay-link', ['team' => $team]),
        [
            'product_id' => 123
        ]
    );

    $response->assertStatus(200);
    $response->assertJson([
        'pay_link' => 'paddle-checkout-link'
    ]);
});
