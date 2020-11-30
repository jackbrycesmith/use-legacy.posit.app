<?php

namespace App\Jobs;

use App\Events\WebhookCallProcessedEvent;
use App\Utils\Ses\SesEventFactory;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use Exception;
use Illuminate\Support\Arr;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\ProcessWebhookJob;

class ProcessSesWebhookJob extends ProcessWebhookJob
{
    public function __construct(WebhookCall $webhookCall)
    {
        parent::__construct($webhookCall);
    }

    public function handle()
    {
        if (! $this->validateMessageFromWebhookCall()) {
            $this->webhookCall->delete();

            return;
        }

        if (! $this->webhookCall->isFirstOfThisSesMessage()) {
            $this->webhookCall->delete();

            return;
        }

        $payload = json_decode($this->webhookCall->payload['Message'], true);

        if (!$messageId = Arr::get($payload, 'mail.messageId')) {
            return;
        }

        if (! Arr::get($payload, 'eventType')) {
            return;
        }

        $sesEvent = SesEventFactory::createForPayload($payload);

        $sesEvent->handle();

        event(new WebhookCallProcessedEvent($this->webhookCall));
    }

    protected function validateMessageFromWebhookCall(): bool
    {
        $validator = new MessageValidator();

        try {
            $message = Message::fromJsonString(json_encode($this->webhookCall->payload));
        } catch (Exception $exception) {
            return false;
        }

        return $validator->isValid($message);
    }
}
