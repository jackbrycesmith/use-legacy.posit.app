<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InAppCredit extends Model
{
    use HasUuid;
    use HasFactory;

    /**
     * Get the owning balance model (e.g. the team).
     *
     * @return MorphTo The morph to relation.
     */
    public function balanceModel(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the owning usage model (i.e. what these credits were used on).
     *
     * @return MorphTo The morph to relation.
     */
    public function usageModel(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the owning initiator model (e.g. which team member).
     *
     * @return MorphTo The morph to relation.
     */
    public function initiatorModel(): MorphTo
    {
        return $this->morphTo();
    }
}
