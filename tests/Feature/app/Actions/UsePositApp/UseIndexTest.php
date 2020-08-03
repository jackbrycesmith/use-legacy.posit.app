<?php

use function Tests\assertInertiaComponent;

it('has use.posit.app index page requires auth', function () {
    $response = $this->get(route('use.index'));

    $response->assertRedirect(route('login'));
});
