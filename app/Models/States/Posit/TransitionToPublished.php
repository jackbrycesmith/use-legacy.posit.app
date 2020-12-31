<?php

namespace App\Models\States\Posit;

use App\Models\InAppCredit;
use App\Models\Posit;
use Illuminate\Support\Facades\DB;
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
        $posit = $this->posit;

        DB::transaction(function () use ($posit) {
            $posit->state = new Published($posit);
            $posit->save();

            InAppCredit::decrease(
                amount: 1,
                balanceModel: $posit->team,
                usageModel: $posit,
                // TODO could add the user/team member who published it as the initiatorModel?
            );

        });

        // TODO e.g. notify me?

        return $this->posit;
    }
}
