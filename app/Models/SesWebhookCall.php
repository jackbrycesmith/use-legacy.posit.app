<?php

namespace App\Models;

use Aws\Sns\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookConfig;

class SesWebhookCall extends WebhookCall
{
    use HasFactory;

    protected $table = 'webhook_calls';

    public static function storeWebhook(WebhookConfig $config, Request $request): WebhookCall
    {
        $message = Message::fromRawPostData();

        return self::create([
            'name' => $config->name,
            'external_id' => $message->toArray()['MessageId'],
            'payload' => $message->toArray(),
        ]);
    }

    public function isFirstOfThisSesMessage(): bool
    {
        $firstMessageId = (int) SesWebhookCall::where('external_id', $this->payload['MessageId'])->min('id');

        return $this->id === $firstMessageId;
    }
}
