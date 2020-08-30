<?php

use App\Actions\Organisation\CreateDraftProposal;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Tests\actingAs;

test('ProposalVideoIntroUpsert 404s with proposal invalid uuid', function () {
    $response = $this->post(route('use.proposal.video-intro', 'blah'));

    $response->assertStatus(404);
});

test('ProposalVideoIntroUpsert requires login', function () {
    $response = $this->post(route('use.proposal.video-intro', Str::uuid()));

    $response->assertRedirect(route('login'));
});

test('ProposalVideoIntroUpsert disallowed if not a proposal user', function () {
    $user = factory(User::class)->create();
    $otherUser = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($otherUser)->post(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ])
    );

    $response->assertStatus(403);
});

test('ProposalVideoIntroUpsert disallowed if incorrect data passed', function () {
    $user = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($user)->postJson(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ])
    );
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'The uuid field is required.']);

    $response = actingAs($user)->postJson(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ]), [
            'uuid' => 'not a uuid'
        ]
    );
    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'The uuid must be a valid UUID.']);
});

test('ProposalVideoIntroUpsert disallowed if temp file does not exist', function () {
    Storage::fake('s3');

    $user = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($user)->postJson(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ]),
        [
            'uuid' => Str::uuid()
        ]
    );

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['uuid' => 'Temp file does not exist.']);
});

test('ProposalVideoIntroUpsert allowed', function () {
    Storage::fake('s3');

    $alreadyUploadedUuid = Str::uuid();
    $tmpFile = UploadedFile::fake()
        ->image('test.jpg')
        ->storeAs("tmp", $alreadyUploadedUuid, 's3');

    $user = factory(User::class)->create();

    $proposal = (new CreateDraftProposal)->actingAs($user)->run([
        'organisation' => $user->organisations->first()
    ]);

    $response = actingAs($user)->postJson(
        route('use.proposal.video-intro', [
            'proposal' => $proposal
        ]),
        [
            'uuid' => $alreadyUploadedUuid
        ]
    );
    $response->assertCreated();
    $response->assertJsonStructure(['data' => ['uuid', 'full_url']]);

    // Assert that the temp file is deleted
    Storage::disk('s3')->assertMissing("tmp/{$alreadyUploadedUuid}");

    // TODO assert that it was added to the media library/new location

    // // Cleanup
    Storage::fake('s3');
});
