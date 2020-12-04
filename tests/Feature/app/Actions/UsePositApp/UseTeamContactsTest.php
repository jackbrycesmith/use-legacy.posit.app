<?php

it('has app/actions/usepositapp/useteamcontacts page', function () {
    $response = $this->get('/app/actions/usepositapp/useteamcontacts');

    $response->assertStatus(200);
})->skip();
