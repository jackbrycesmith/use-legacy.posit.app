<?php

namespace App\Policies;

use App\Models\OrganisationContact;
use App\Models\Proposal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProposalPolicy
{
    use HandlesAuthorization;

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
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function view(User $user, Proposal $proposal)
    {
        // TODO Would be nice to not load models? check hasmanydeep package
        if (! $proposal->users->contains($user)) {
            return Response::deny('You do not have permission to view this proposal.');
        }

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
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function update(User $user, Proposal $proposal)
    {
        // TODO Would be nice to not load models? check hasmanydeep package
        // TODO whether a ProposalUser has read/write abilities
        if (! $proposal->users->contains($user)) {
            return Response::deny('You do not have permission to view this proposal.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can upsert a recipient on the model.
     *
     * @param \App\Models\User $user The user
     * @param \App\Models\Proposal $proposal The proposal
     * @param \App\Models\OrganisationContac $contact The contact
     *
     * @return mixed
     */
    public function upsertRecipient(User $user, Proposal $proposal, ?OrganisationContact $contact)
    {
        // TODO Would be nice to not load models? check hasmanydeep package
        // TODO whether a ProposalUser has read/write abilities
        if (! $proposal->users->contains($user)) {
            return Response::deny('You do not have permission to view this proposal.');
        }

        if (! is_null($contact)) {
            if ($contact->organisation_id !== $proposal->organisation_id) {
                return Response::deny('Contact does not belong to the proposal organisation.');
            }
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function delete(User $user, Proposal $proposal)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function restore(User $user, Proposal $proposal)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Proposal  $proposal
     * @return mixed
     */
    public function forceDelete(User $user, Proposal $proposal)
    {
        //
    }
}
