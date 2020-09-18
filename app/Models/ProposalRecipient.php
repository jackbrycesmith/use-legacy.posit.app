<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProposalRecipient extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'proposal_id' => 'integer',
        'team_contact_id' => 'integer',
    ];

    /**
     * Get the proposal
     *
     * @return BelongsTo The belongs to relation.
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the team contact
     *
     * @return BelongsTo The belongs to relation.
     */
    public function teamContact(): BelongsTo
    {
        return $this->belongsTo(TeamContact::class);
    }
}
