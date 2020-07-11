<?php

use function Tests\assertInertiaComponent;

it('shows new ProposalNewTryout page if not logged in', function () {
    $response = $this->get('/proposals/new');
    $response->assertStatus(200);
    assertInertiaComponent($response, 'Use/ProposalNewTryout');
});
