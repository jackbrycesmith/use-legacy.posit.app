<?php

namespace App\Observers;

use App\Actions\User\CreatePersonalOrg;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param \App\Models\User $user The user
     *
     * @return void
     */
    public function created(User $user)
    {
        // TODO optimisation; Do I really need to create an org everytime a new user is created?
        // I could just do it in the background if they create a new proposal & don't have one yet...
        CreatePersonalOrg::run($user);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param \App\Models\User $user The user
     *
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param \App\Models\User $user The user
     *
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param \App\Models\User $user The user
     *
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param \App\Models\User $user The user
     *
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
