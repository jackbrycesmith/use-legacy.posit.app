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
        if (! $user->isMemberOfOrganisation($organisation)) {
            return Response::deny('You do not belong to the provided organisation.');
        }

        // TODO other proposal creation restrictions...
        return Response::allow();
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
        if (! $user->isMemberOfOrganisation($organisation)) {
            return Response::deny('You do not belong to the provided organisation.');
        }

        // TODO other view restrictions...
        return Response::allow();
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
        if (! $user->isMemberOfOrganisation($organisation)) {
            return Response::deny('You do not belong to the provided organisation.');
        }

        // TODO other update restrictions...
        return Response::allow();
    }

    /**
     * Determine whether the user can start the oauth connect process with stripe.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Organisation  $organisation
     * @return mixed
     */
    public function startStripeConnectOauth(User $user, Organisation $organisation)
    {
        if (! $user->isMemberOfOrganisation($organisation)) {
            return Response::deny('You do not belong to the provided organisation.');
        }

        if (! is_null($organisation->stripeAccount)) {
            return Response::deny('Stripe account already connected for this org.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can disconnect the organsiation stripe account.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Organisation  $organisation
     * @return mixed
     */
    public function disconnectStripeAccount(User $user, Organisation $organisation)
    {
        if (! $user->isMemberOfOrganisation($organisation)) {
            return Response::deny('You do not belong to the provided organisation.');
        }

        $stripeAccount = $organisation->stripeAccount;
        if (is_null($stripeAccount)) {
            return Response::deny('Stripe account not connected.');
        }

        if (! is_null($stripeAccount->deleted_at)) {
            return Response::deny('Stripe account not connected.');
        }

        // TODO other update restrictions...
        return Response::allow();
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
