<?php

use App\Actions\Mail\AddSesConfigurationSet;
use Illuminate\Mail\Events\MessageSending;

it('listens for event', function () {
    $this->partialMock(AddSesConfigurationSet::class, function ($mock) {
        $mock->shouldReceive('handle')->once();
    });

    event(new MessageSending(null));
});
