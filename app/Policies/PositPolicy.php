<?php

namespace App\Policies;

use App\Enums\PositType;
use App\Models\States\Posit\Published;
use App\Models\TeamContact;
use App\Models\Posit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PositPolicy
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
     * @param \App\Models\User  $user
     * @param \App\Models\Posit  $posit
     *
     * @return mixed
     */
    public function view(User $user, Posit $posit)
    {
        if (! $user->belongsToTeam($posit->team)) {
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
     * @param  \App\Models\Posit  $posit
     * @return mixed
     */
    public function update(User $user, Posit $posit)
    {
        // TODO whether a ProposalUser has read/write abilities
        if (! $user->belongsToTeam($posit->team)) {
            return Response::deny('You do not have permission to view this proposal.');
        }

        if (! $posit->state->canUpdateThePosit()) {
            return Response::deny("Cannot update posit when in state: {$posit->state}.");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can publish the proposal.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posit  $posit
     * @return mixed
     */
    public function publish(User $user, Posit $posit)
    {
        // Cannot publish if not part of the team
        if (! $user->belongsToTeam($posit->team)) {
            return Response::deny('You do not have permission to view this proposal.');
        }

        // Is in a state that can be published...
        if (! in_array(Published::getMorphClass(), $posit->state->transitionableStates())) {
            return Response::deny("Cannot publish posit in state: {$posit->state}.");
        }

        // If accept_and_pay
        if ($posit->type->equals(PositType::accept_and_pay())) {
            // Must have deposit & project value
            if ($posit->value_amount < 1) {
                return Response::deny("Cannot publish posit unless project value is at least 1");
            }

            $depositAmount = $posit->depositPayment?->amount ?? 0;
            // Deposit must be minimum amount (at least 1)
            if ($depositAmount < 1) {
                return Response::deny("Cannot publish posit unless deposit amount is at least 1");
            }

            // Deposit must not exceed project value
            if ($depositAmount > $posit->value_amount) {
                return Response::deny("Deposit amount must not exceed the project value");
            }
        }

        // TODO: Cannot publish if insufficient credits
        $creditBalance = $posit->team?->inAppCreditBalance() ?? 0;
        if ($creditBalance < 1) {
            return Response::deny("Publishing requires 1 credit.");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can upsert a recipient on the model.
     *
     * @param \App\Models\User $user The user
     * @param \App\Models\Posit $posit The posit
     * @param \App\Models\OrganisationContac $contact The contact
     *
     * @return mixed
     */
    public function upsertRecipient(User $user, Posit $posit, ?TeamContact $contact)
    {
        // TODO Would be nice to not load models? check hasmanydeep package
        // TODO whether a ProposalUser has read/write abilities
        if (! $user->belongsToTeam($posit->team)) {
            return Response::deny('You do not have permission to action this proposal.');
        }

        if (! is_null($contact)) {
            if ($contact->team_id !== $posit->team_id) {
                return Response::deny('Contact does not belong to the proposal team.');
            }
        }

        return Response::allow();
    }

    /**
     * Determine if the proposal can be accepted with a payment.
     *
     * @param \App\Models\User|null $user The user
     * @param \App\Models\Posit $posit The posit
     *
     * @return mixed
     */
    public function publicAcceptWithPayment(?User $user, Posit $posit)
    {
        // TODO check proposal state etc...
        // TODO
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Posit $posit
     *
     * @return mixed
     */
    public function delete(User $user, Posit $posit)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posit  $posit
     * @return mixed
     */
    public function restore(User $user, Posit $posit)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Posit  $posit
     * @return mixed
     */
    public function forceDelete(User $user, Posit $posit)
    {
        //
    }
}
