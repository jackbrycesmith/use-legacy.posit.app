<?php

namespace App\Events;

use App\Models\StripeAccount;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StripeAccountDetailsUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * @var StripeAccount
     */
    public $account;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(StripeAccount $account)
    {
        $this->account = $account;
    }
}
