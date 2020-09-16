<?php

namespace App\Broadcasting;

use App\Models\Proposal;
use App\Models\User;

class ProposalIntroVideoChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @return array|bool
     */
    public function join(User $user, string $proposalUuid)
    {
        $proposal = Proposal::where('uuid', $proposalUuid)->first();
        return $proposal->users->contains($user);
    }
}
