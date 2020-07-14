<?php

use App\Actions\PubPositApp\PubIndex;
use function Tests\assertInertiaComponent;

it('gets pub.posit.app index page', function () {
    $response = $this->get(action([PubIndex::class]));
    $response->assertStatus(200);
    assertInertiaComponent($response, 'Pub/Index');
});
