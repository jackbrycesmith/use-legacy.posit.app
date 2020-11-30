<?php

namespace App\Utils\Ses;

use Aws\Sns\Message;
use Exception;
use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class ProcessSesWebhooksProfile implements WebhookProfile
{
    public function shouldProcess(Request $request): bool
    {
        try {
            $message = Message::fromRawPostData();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
