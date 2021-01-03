<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramMeNow extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     */
    public function __construct(public string $message)
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the telegram representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toTelegram($notifiable)
    {
        // TODO they are pretty restrictive in what they allow, so escape the required characters
        // See https://core.telegram.org/bots/api#markdownv2-style
        $content = $this->message;

        return TelegramMessage::create()
            ->to(config('telegram.site_admin_chat_id'))
            ->options([
                'parse_mode' => 'MarkdownV2'
                // See https://core.telegram.org/bots/api#markdownv2-style
            ])
            ->content($content);
    }
}
