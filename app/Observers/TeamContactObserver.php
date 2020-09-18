<?php

namespace App\Observers;

use App\Models\TeamContact;

class TeamContactObserver
{
    /**
     * Handle the organisation contact "creating" event.
     *
     * @param  \App\Models\TeamContact  $teamContact
     * @return void
     */
    public function creating(TeamContact $teamContact)
    {
        if (is_null($teamContact->access_code)) {
            $teamContact->access_code = TeamContact::createAccessCode();
        }
    }

    /**
     * Handle the organisation contact "created" event.
     *
     * @param  \App\Models\TeamContact  $teamContact
     * @return void
     */
    public function created(TeamContact $teamContact)
    {
        //
    }

    /**
     * Handle the organisation contact "updated" event.
     *
     * @param  \App\Models\TeamContact  $teamContact
     * @return void
     */
    public function updated(TeamContact $teamContact)
    {
        //
    }

    /**
     * Handle the organisation contact "deleted" event.
     *
     * @param  \App\Models\TeamContact  $teamContact
     * @return void
     */
    public function deleted(TeamContact $teamContact)
    {
        //
    }

    /**
     * Handle the organisation contact "restored" event.
     *
     * @param  \App\Models\TeamContact  $teamContact
     * @return void
     */
    public function restored(TeamContact $teamContact)
    {
        //
    }

    /**
     * Handle the organisation contact "force deleted" event.
     *
     * @param  \App\Models\TeamContact  $teamContact
     * @return void
     */
    public function forceDeleted(TeamContact $teamContact)
    {
        //
    }
}
