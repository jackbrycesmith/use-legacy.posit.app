<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProposalUser extends Pivot
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
        'organisation_member_id' => 'integer',
        'organisation_contact_id' => 'integer',
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
     * Get the organisation member
     *
     * @return BelongsTo The belongs to relation.
     */
    public function organisationMember(): BelongsTo
    {
        return $this->belongsTo(OrganisationMember::class);
    }

    /**
     * Get the organisation contact
     *
     * @return BelongsTo The belongs to relation.
     */
    public function organisationContact(): BelongsTo
    {
        return $this->belongsTo(OrganisationContact::class);
    }
}
