<?php

use App\Actions\Team\CreateDraftPosit;
use App\Enums\PositType;
use App\Models\InAppCredit;
use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\States\Posit\Published;
use App\Models\Team;
use App\Models\User;
use function Tests\actingAs;

test('to publish posit it must exist', function () {
    $response = $this->put(route('use.submit.publish-posit', ['posit' => 'blah']));
    $response->assertStatus(404);
});

test('to publish posit it requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    InAppCredit::increase(1, $team);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.publish-posit', ['posit' => $posit]));
    $response->assertRedirect(route('login'));
    assertFalse($posit->state->hasBeenInPublishedState());
});

test('to publish posit, user must be a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    InAppCredit::increase(1, $team);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.publish-posit', ['posit' => $posit]));

    $response->assertStatus(403);
    assertFalse($posit->state->hasBeenInPublishedState());
});

test('to publish posit, it must not have already been published', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    InAppCredit::increase(1, $team);
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => Published::class]);

    $response = actingAs($user)->putJson(route('use.submit.publish-posit', ['posit' => $posit]));

    $response->assertStatus(403);
    // TODO (requires laravel actions v2)
    // $response->assertJsonFragment([
    //     'message' => 'This proposal has already been published.'
    // ]);
});

test('to publish posit that is accept_and_pay type, it must meet extra requirements', function (AcceptAndPayRequirementsTestData $testData) {

    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    InAppCredit::increase(1, $team);
    $posit = Posit::factory()->create([
        'team_id' => $team->id,
        'type' => PositType::accept_and_pay(),
        'value_amount' => $testData->projectValueAmount
    ]);

    if (! is_null($testData->depositAmount)) {
        $positDeposit = $posit->depositPayment()->firstOrCreate([
            'type' => PositPayment::TYPE_DEPOSIT,
            'amount' => $testData->depositAmount
        ]);
    }

    $response = actingAs($user)->putJson(route('use.submit.publish-posit', ['posit' => $posit]));
    $response->assertStatus(403);
})->with([
    new AcceptAndPayRequirementsTestData(),
    // Project value must be at least 1
    new AcceptAndPayRequirementsTestData(
        projectValueAmount: 0.5
    ),
    // Deposit must be at least 1
    new AcceptAndPayRequirementsTestData(
        projectValueAmount: 1,
        depositAmount: 0.5
    ),
    // Deposit must not exceed project value
    new AcceptAndPayRequirementsTestData(
        projectValueAmount: 1,
        depositAmount: 2
    ),
]);

test('to publish posit requires at least 1 credit balance', function () {

    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = Posit::factory()->create([
        'team_id' => $team->id,
        'type' => PositType::accept_and_pay(),
        'value_amount' => 1
    ]);
    $positDeposit = $posit->depositPayment()->firstOrCreate([
        'type' => PositPayment::TYPE_DEPOSIT,
        'amount' => 1
    ]);

    $response = actingAs($user)->putJson(route('use.submit.publish-posit', ['posit' => $posit]));
    $response->assertStatus(403);
});

test('can publish posit accept_and_pay type', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    InAppCredit::increase(1, $team);
    $posit = Posit::factory()->create([
        'team_id' => $team->id,
        'type' => PositType::accept_and_pay(),
        'value_amount' => 1
    ]);
    $positDeposit = $posit->depositPayment()->firstOrCreate([
        'type' => PositPayment::TYPE_DEPOSIT,
        'amount' => 1
    ]);

    $response = actingAs($user)->putJson(route('use.submit.publish-posit', ['posit' => $posit]));
    $response->assertStatus(204);

    $posit->refresh();
    expect($posit->state)->toBeInstanceOf(Published::class);
});

test('can publish posit accept_only type', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    InAppCredit::increase(1, $team);
    $posit = Posit::factory()->create(['team_id' => $team->id]);

    $response = actingAs($user)->putJson(route('use.submit.publish-posit', ['posit' => $posit]));
    $response->assertStatus(204);

    $posit->refresh();
    expect($posit->state)->toBeInstanceOf(Published::class);
});

// Test data classes

class AcceptAndPayRequirementsTestData {
    public function __construct(
        public ?int $projectValueAmount = null,
        public ?int $depositAmount = null,
        // TODO: when upgrade to laravel actions 2 i'll be able to test policy responses
        public ?string $authDeniedMessage = null,
    ){}
}
