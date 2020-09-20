<?php

use App\Actions\Team\CreateDraftProposal;
use App\Jobs\ConvertVideoForDownloading;
use App\Models\Team;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Tests\actingAs;

test('ProposalVideoIntroUpsert 404s with proposal invalid uuid', function () {
    Bus::fake();
    $response = $this->post(route('use.proposal.video-intro', 'blah'));
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    $response->assertStatus(404);
});

test('ProposalVideoIntroUpsert requires login', function () {
    Bus::fake();
    $response = $this->post(route('use.proposal.video-intro', Str::uuid()));
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    $response->assertRedirect(route('login'));
});

test('ProposalVideoIntroUpsert disallowed if not a proposal user', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();

    Bus::fake();
    $response = actingAs($otherUser)->post(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ])
    );
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    $response->assertStatus(403);
});

test('ProposalVideoIntroUpsert disallowed if incorrect data passed', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    Bus::fake();
    $response = actingAs($user)->postJson(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ])
    );
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'The uuid field is required.']);
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    Bus::fake();
    $response = actingAs($user)->postJson(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ]), [
            'uuid' => 'not a uuid'
        ]
    );
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'The uuid must be a valid UUID.']);
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);
});

test('ProposalVideoIntroUpsert disallowed if temp file does not exist', function () {
    Storage::fake('s3');

    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    Bus::fake();
    $response = actingAs($user)->postJson(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ]),
        [
            'uuid' => Str::uuid()
        ]
    );
    $response->dump();
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'Temp file does not exist.']);
})->skip();

test('ProposalVideoIntroUpsert allowed', function () {
    Storage::fake('s3');

    $alreadyUploadedUuid = Str::uuid();
    $tmpFile = UploadedFile::fake()
        ->image('test.jpg')
        ->storeAs("tmp", $alreadyUploadedUuid, 's3');

    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'team' => $team
    ]);

    Bus::fake();
    $response = actingAs($user)->postJson(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ]),
        [
            'uuid' => $alreadyUploadedUuid
        ]
    );

    $response->dump();
    $response->assertCreated();
    $response->assertJsonStructure(['data' => ['uuid']]);

    $createdVideo = Video::findByUuid($response->json('data.uuid'));

    assertEquals('proposal', $createdVideo->model_type);
    assertEquals($proposal->id, $createdVideo->model_id);

    Bus::assertDispatched(ConvertVideoForDownloading::class);

    // Assert that the temp file is deleted
    // Storage::disk('s3')->assertMissing("tmp/{$alreadyUploadedUuid}");

    // TODO assert that it was added to the media library/new location

    // // Cleanup
    Storage::fake('s3');
})->skip();
