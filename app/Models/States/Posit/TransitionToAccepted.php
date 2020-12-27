<?php

namespace App\Models\States\Posit;

use App\Models\Posit;
use App\Notifications\Team\TeamPositAccepted;
use Illuminate\Support\Facades\DB;
use Spatie\ModelStates\Transition;

class TransitionToAccepted extends Transition
{
    private Posit $posit;

    /**
     * Constructs a new instance.
     *
     * @param \App\Models\Posit $posit The posit
     */
    public function __construct(Posit $posit)
    {
        $this->posit = $posit;
    }

    /**
     * Handle this transition.
     *
     * @return Posit
     */
    public function handle(): Posit
    {
        DB::transaction(function () {
            $this->posit->state = new Accepted($this->posit);
            $this->posit->save();
        });

        $this->posit->team->notify(new TeamPositAccepted($this->posit));

        return $this->posit;
    }
}
