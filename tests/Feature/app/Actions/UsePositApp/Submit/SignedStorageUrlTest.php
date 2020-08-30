<?php

use App\Models\User;
use function Tests\actingAs;

test('signedstorageurl endpoint requires user logged in...', function () {
    $response = $this->post(route('use.submit.signed-storage-url'));
    $response->assertRedirect(route('login'));
});

test('can upload to s3 from created presigned url', function () {
    $user = factory(User::class)->create();
    $response = actingAs($user)->postJson(route('use.submit.signed-storage-url', [
        'content_type' => 'text/plain',
    ]));
    $response->assertStatus(201);
    $response->assertJsonStructure([
        'uuid',
        'bucket',
        'key',
        'url',
        'headers' => [
            // 'x-amz-acl', TODO this should be included for filebase, not minio
            'Content-Type'
        ],
    ]);
});
