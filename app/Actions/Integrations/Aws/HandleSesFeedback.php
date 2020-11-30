<?php

namespace App\Actions\Integrations\Aws;

use App\Utils\Ses\SesWebhookConfig;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;
use Spatie\WebhookClient\WebhookProcessor;

class HandleSesFeedback extends Action
{
    /**
     * Execute the action and return a result.
     *
     * @param \Illuminate\Http\Request $request The request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $webhookConfig = SesWebhookConfig::get();

        (new WebhookProcessor($request, $webhookConfig))->process();

        return response()->json(['message' => 'ok']);
    }
}
