<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelescopeReport extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($telescopeEntries = null)
    {
        $this->telescopeEntries = $telescopeEntries;
    }

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
        return TelegramMessage::create()
            ->to(config('telegram.site_admin_chat_id'))
            ->options([
                'parse_mode' => 'HTML'
            ])
            ->view(
                'reports.telescope-telegram-report',
                ['entries' => $this->telescopeEntries]
            )
            ->button('View in Telescope', route('telescope'));
    }
}
