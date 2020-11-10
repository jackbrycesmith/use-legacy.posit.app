<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\TeamContact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TeamPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        return $user->belongsToTeam($team);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function update(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can add team members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function addTeamMember(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can update team member permissions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function updateTeamMember(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can remove team members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function removeTeamMember(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @return mixed
     */
    public function delete(User $user, Team $team)
    {
        return $user->ownsTeam($team);
    }

    /**
     * Determine whether the user can create a proposal for an team.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team The team
     *
     * @return mixed
     */
    public function createDraftProposal(User $user, Team $team)
    {
        if (! $user->belongsToTeam($team)) {
            return Response::deny('You do not belong to the team.');
        }

        // TODO other proposal creation restrictions...
        return Response::allow();
    }

    /**
     * Determine whether the user can do anything to a proposal resource.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Team $team The team
     *
     * @return mixed
     */
    public function actionProposal(User $user, Team $team)
    {
        // TODO something more elaborate; e.g. ProposalUser's...

        if (! $user->belongsToTeam($team)) {
            return Response::deny('You do not belong to the team.');
        }

        // TODO other proposal creation restrictions...
        return Response::allow();
    }

    /**
     * Determine whether the user can upsert a contact on the model.
     *
     * @param \App\Models\User $user The user
     * @param \App\Models\Team $team The team
     * @param \App\Models\TeamContact $contact The contact
     *
     * @return mixed
     */
    public function upsertContact(User $user, Team $team, ?TeamContact $contact)
    {
        if (is_null($contact)) {
            return $user->belongsToTeam($team);
        }

        if (! $user->belongsToTeam($team)) {
            return Response::deny('You do not belong to the team.');
        }

        if ($team->id !== $contact->team_id) {
            return Response::deny('Contact does not belong to the team.');
        }

        return Response::allow();
    }
}
