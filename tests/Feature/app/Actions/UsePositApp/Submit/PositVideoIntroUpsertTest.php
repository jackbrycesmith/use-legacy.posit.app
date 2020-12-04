<?php

use App\Actions\Team\CreateDraftPosit;
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
    $response = $this->post(route('use.posit.video-intro', 'blah'));
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    $response->assertStatus(404);
});

test('ProposalVideoIntroUpsert requires login', function () {
    Bus::fake();
    $response = $this->post(route('use.posit.video-intro', Str::uuid()));
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    $response->assertRedirect(route('login'));
});

test('ProposalVideoIntroUpsert disallowed if not a proposal user', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    $otherUser = User::factory()->create();

    Bus::fake();
    $response = actingAs($otherUser)->post(
        route('use.posit.video-intro', [
            'posit' => $posit
        ])
    );
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    $response->assertStatus(403);
});

test('ProposalVideoIntroUpsert disallowed if incorrect data passed', function () {
    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    Bus::fake();
    $response = actingAs($user)->postJson(
        route('use.posit.video-intro', [
            'posit' => $posit
        ])
    );
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'The uuid field is required.']);
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    Bus::fake();
    $response = actingAs($user)->postJson(
        route('use.posit.video-intro', [
            'posit' => $posit
        ]), [
            'uuid' => 'not a uuid'
        ]
    );
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'The uuid must be a valid UUID.']);
    Bus::assertNotDispatched(ConvertVideoForDownloading::class);
});

test('ProposalVideoIntroUpsert disallowed if temp file does not exist', function () {
    Storage::fake(config('filesystems.s3_uploads_disk'));

    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    Bus::fake();
    $response = actingAs($user)->postJson(
        route('use.posit.video-intro', [
            'posit' => $posit
        ]),
        [
            'uuid' => Str::uuid()
        ]
    );

    Bus::assertNotDispatched(ConvertVideoForDownloading::class);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'Temp file does not exist.']);
});

test('ProposalVideoIntroUpsert allowed', function () {
    Storage::fake(config('filesystems.s3_uploads_disk'));

    $alreadyUploadedUuid = Str::uuid();
    $tmpFile = UploadedFile::fake()
        ->image('test.jpg')
        ->storeAs("tmp", $alreadyUploadedUuid, config('filesystems.s3_uploads_disk'));

    $user = User::factory()->create();
    $team = Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
    $posit = (new CreateDraftPosit)->actingAs($user)->run([
        'team' => $team
    ]);

    Bus::fake();
    $response = actingAs($user)->postJson(
        route('use.posit.video-intro', [
            'posit' => $posit
        ]),
        [
            'uuid' => $alreadyUploadedUuid
        ]
    );

    $response->assertCreated();
    $response->assertJsonStructure(['data' => ['uuid']]);

    $createdVideo = Video::findByUuid($response->json('data.uuid'));

    assertEquals('posit', $createdVideo->model_type);
    assertEquals($posit->id, $createdVideo->model_id);

    Bus::assertDispatched(ConvertVideoForDownloading::class);

    // Assert that the temp file is deleted
    // Storage::disk(config('filesystems.s3_uploads_disk'))->assertMissing("tmp/{$alreadyUploadedUuid}");

    // TODO assert that it was added to the media library/new location

    // // Cleanup
    Storage::fake(config('filesystems.s3_uploads_disk'));
});
