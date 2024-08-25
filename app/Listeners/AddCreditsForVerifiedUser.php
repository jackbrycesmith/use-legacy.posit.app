<?php

namespace App\Listeners;

use App\Models\InAppCredit;
use App\Models\Team;
use Illuminate\Auth\Events\Verified;

class AddCreditsForVerifiedUser
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $team = Team::where('user_id', $event->user->id)->first();
        $team->posits()->create(['name' => 'My First Posit']);
        InAppCredit::increase((int) config('posit-settings.verified_user_credits'), $team);
    }
}
