<?php

namespace App\Utils\Ses;

use App\Jobs\ProcessSesWebhookJob;
use App\Models\SesWebhookCall;
use App\Utils\Ses\ProcessSesWebhooksProfile;
use App\Utils\Ses\SesSignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class SesWebhookConfig
{
    public static function get(): WebhookConfig
    {
        return new WebhookConfig([
            'name' => 'ses-feedback',
            'header_name' => 'Signature',
            'signature_validator' => SesSignatureValidator::class,
            'webhook_profile' =>  ProcessSesWebhooksProfile::class,
            'webhook_model' => SesWebhookCall::class,
            'process_webhook_job' => ProcessSesWebhookJob::class,
        ]);
    }
}
