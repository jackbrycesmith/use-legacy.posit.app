<?php

namespace App\Actions\Webhooks;

use App\Events\WebhookCallProcessedEvent;
use Lorisleiva\Actions\Action;

class HandleSetWebhookCallProcessed extends Action
{
    public function getAttributesFromEvent(WebhookCallProcessedEvent $event)
    {
        return [
            'webhookCall' => $event->webhookCall,
        ];
    }

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->webhookCall->update([
            'processed_at' => now(),
        ]);
    }
}
