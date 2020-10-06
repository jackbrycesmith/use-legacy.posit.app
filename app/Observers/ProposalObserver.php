<?php

namespace App\Observers;

use App\Models\Proposal;

class ProposalObserver
{
    /**
     * Handle the proposal "creating" event.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return void
     */
    public function creating(Proposal $proposal)
    {
        if (is_null($proposal->theme)) {
            $proposal->theme = $proposal->defaultTheme();
        }
    }

    /**
     * Handle the proposal "created" event.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return void
     */
    public function created(Proposal $proposal)
    {
        $proposal->setStatus(Proposal::STATUS_DRAFT);
    }

    /**
     * Handle the proposal "updated" event.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return void
     */
    public function updated(Proposal $proposal)
    {
        //
    }

    /**
     * Handle the proposal "deleted" event.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return void
     */
    public function deleted(Proposal $proposal)
    {
        // TODO cleanup e.g. statuses...
    }

    /**
     * Handle the proposal "restored" event.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return void
     */
    public function restored(Proposal $proposal)
    {
        //
    }

    /**
     * Handle the proposal "force deleted" event.
     *
     * @param  \App\Models\Proposal  $proposal
     * @return void
     */
    public function forceDeleted(Proposal $proposal)
    {
        //
    }
}
