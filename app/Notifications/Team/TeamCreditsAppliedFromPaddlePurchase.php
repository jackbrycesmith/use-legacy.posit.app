<?php

namespace App\Notifications\Team;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamCreditsAppliedFromPaddlePurchase extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param int $creditsAmount
     */
    public function __construct(public int $creditsAmount)
    {
        $this->afterCommit = true;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('use.index');

        return (new MailMessage)
                    ->subject("✅ {$this->creditsAmount} Posit Credits Applied")
                    ->line("Thanks again for your purchase.")
                    ->line("{$this->creditsAmount} credits have now been applied to your account 🚀")
                    ->action('Visit use.posit.app', $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
