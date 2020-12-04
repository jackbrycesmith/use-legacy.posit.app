<?php

namespace App\Broadcasting;

use App\Models\Posit;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class PositIntroVideoChannel
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
    public function join(User $user, string $positUuid)
    {
        $posit = Posit::where('uuid', $positUuid)->first();
        if (is_null($posit)) {
            return false;
        }

        return Gate::forUser($user)->allows('view', $posit);
    }
}
