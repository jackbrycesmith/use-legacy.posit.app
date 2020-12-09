<?php

namespace App\Models\States\Posit;

use App\Models\Posit;
use Spatie\ModelStates\Transition;

class TransitionToPublished extends Transition
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
        // TODO some custom stuff?

        return $this->posit;
    }
}
