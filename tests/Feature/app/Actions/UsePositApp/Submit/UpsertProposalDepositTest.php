<?php

use App\Actions\Team\CreateDraftProposal;
use App\Models\ProposalPayment;
use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;

test('updating proposal deposit requires proposal exist', function () {
    $response = $this->put(route('use.submit.upsert-proposal-deposit', ['proposal' => 'blah']));
    $response->assertStatus(404);
});

test('updating proposal deposit requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-proposal-deposit', ['proposal' => $proposal]));
    $response->assertRedirect(route('login'));
});

test('user cannot update proposal deposit if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-proposal-deposit', ['proposal' => $proposal]));

    $response->assertStatus(403);
});

test('it creates proposal deposit payment if non-existant', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $proposal->refresh();
    assertNull($proposal->depositPayment);
    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-deposit', ['proposal' => $proposal]),
        [
            //
        ]
    );

    $proposal->refresh();
    $response->assertStatus(204);
    assertNotNull($proposal->depositPayment);
});

test('user can update proposal deposit payment amount', function ($amount) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $proposalDeposit = $proposal->depositPayment()->firstOrCreate([
        'type' => ProposalPayment::TYPE_DEPOSIT
    ]);

    $response = actingAs($user)->putJson(
        route('use.submit.upsert-proposal-deposit', ['proposal' => $proposal]),
        [
            'amount' => $amount
        ]
    );

    $proposal->refresh();
    assertEquals($proposalDeposit->id, $proposal->depositPayment->id);
    $response->assertStatus(204);

    $amount = $amount == '' ? null : $amount; // Empty string gets converted to null
    $this->assertDatabaseHas('proposal_payments', [
        'id' => $proposalDeposit->id,
        'proposal_id' => $proposal->id,
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
