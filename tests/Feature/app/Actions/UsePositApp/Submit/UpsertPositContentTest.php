<?php

use App\Actions\Team\CreateDraftPosit;
use App\Models\Posit;
use App\Models\States\Posit\PositState;
use App\Models\Team;
use App\Models\User;
use App\Models\Values\PositContent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use function Tests\actingAs;

test('updating proposal content requires proposal exist', function () {
    $response = $this->put(route('use.submit.upsert-posit-content', ['posit' => 'blah']));
    $response->assertStatus(404);
});

test('updating proposal content requires login', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $response = $this->put(route('use.submit.upsert-posit-content', ['posit' => $posit]));
    $response->assertRedirect(route('login'));
});

test('user cannot update proposal content if not a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();
    $response = actingAs($otherUser)->put(route('use.submit.upsert-posit-content', ['posit' => $posit]));

    $response->assertStatus(403);
});

test('user cannot update proposal content in certain states', function ($state) {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = Posit::factory()->create(['team_id' => $team->id, 'state' => $state]);

    Event::fake();

    $positContent = [
        'type' => 'document',
        'content' => null
    ];

    $response = actingAs($user)->put(
        route('use.submit.upsert-posit-content', ['posit' => $posit]),
        $positContent
    );

    $response->assertStatus(403);
})->with(
    PositState::all()->except(PositState::statesThatCanUpdateThePosit())->keys()
);

test('user can update proposal content if a team member', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $positContent = [
        'type' => 'document',
        'content' => null
    ];

    $response = actingAs($user)->put(
        route('use.submit.upsert-posit-content', ['posit' => $posit]),
        $positContent
    );

    $response->assertStatus(200);
    $posit->refresh();
    $positDbEntryContent = DB::table('posits')->select('content')->find($posit->id)->content;
    $savedDbPositContentDecrypted = $posit->fromEncryptedString($positDbEntryContent);

    expect($savedDbPositContentDecrypted)->toEqual(json_encode($positContent));
    expect($posit->content)->toBeInstanceOf(PositContent::class);

});
