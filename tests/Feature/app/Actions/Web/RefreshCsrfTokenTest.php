<?php

it('sets XSRF-TOKEN cookie', function () {
    $response = $this->post('/api/refresh-csrf-token');

    $response->assertStatus(200);
    $response->assertCookie('XSRF-TOKEN');
});
