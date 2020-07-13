<?php

use App\Actions\Proposal\Pub\GetPubProposalIndex;
use function Tests\assertInertiaComponent;

it('gets pub.posit.app index page', function () {
    $response = $this->get(action([GetPubProposalIndex::class]));
    $response->assertStatus(200);
    assertInertiaComponent($response, 'Pub/Index');
});
