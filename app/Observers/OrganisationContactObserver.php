<?php

namespace App\Observers;

use App\Models\OrganisationContact;

class OrganisationContactObserver
{
    /**
     * Handle the organisation contact "creating" event.
     *
     * @param  \App\Models\OrganisationContact  $organisationContact
     * @return void
     */
    public function creating(OrganisationContact $organisationContact)
    {
        $organisationContact->access_code = OrganisationContact::createAccessCode();
    }

    /**
     * Handle the organisation contact "created" event.
     *
     * @param  \App\Models\OrganisationContact  $organisationContact
     * @return void
     */
    public function created(OrganisationContact $organisationContact)
    {
        //
    }

    /**
     * Handle the organisation contact "updated" event.
     *
     * @param  \App\Models\OrganisationContact  $organisationContact
     * @return void
     */
    public function updated(OrganisationContact $organisationContact)
    {
        //
    }

    /**
     * Handle the organisation contact "deleted" event.
     *
     * @param  \App\Models\OrganisationContact  $organisationContact
     * @return void
     */
    public function deleted(OrganisationContact $organisationContact)
    {
        //
    }

    /**
     * Handle the organisation contact "restored" event.
     *
     * @param  \App\Models\OrganisationContact  $organisationContact
     * @return void
     */
    public function restored(OrganisationContact $organisationContact)
    {
        //
    }

    /**
     * Handle the organisation contact "force deleted" event.
     *
     * @param  \App\Models\OrganisationContact  $organisationContact
     * @return void
     */
    public function forceDeleted(OrganisationContact $organisationContact)
    {
        //
    }
}
