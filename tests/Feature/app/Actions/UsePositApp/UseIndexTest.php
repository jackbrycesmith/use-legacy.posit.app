<?php

use function Tests\assertInertiaComponent;

it('has use.posit.app index page', function () {
    $response = $this->get(route('use.index'));

    $response->assertStatus(200);
    assertInertiaComponent($response, 'Use/Index');
});
