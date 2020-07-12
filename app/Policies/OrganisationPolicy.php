<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrganisationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create a proposal for an organisation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Organisation  $organisation
     * @return mixed
     */
    public function createDraftProposal(User $user, Organisation $organisation)
    {
        // The user must be a member of the given organisation
        if (! $user->organisations()->where('id', $organisation->id)->exists()) {
            Response::deny('You do not belong to the provided organisation.');
        }

        // TODO other proposal creation restrictions...
        Response::allow();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Organisation  $organisation
     * @return mixed
     */
    public function view(User $user, Organisation $organisation)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Organisation  $organisation
     * @return mixed
     */
    public function update(User $user, Organisation $organisation)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Organisation  $organisation
     * @return mixed
     */
    public function delete(User $user, Organisation $organisation)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Organisation  $organisation
     * @return mixed
     */
    public function restore(User $user, Organisation $organisation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Organisation  $organisation
     * @return mixed
     */
    public function forceDelete(User $user, Organisation $organisation)
    {
        //
    }
}
