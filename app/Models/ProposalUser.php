<?php

namespace App\Models;

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
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the organisation member
     */
    public function organisationMember()
    {
        return $this->belongsTo(OrganisationMember::class);
    }

    /**
     * Get the OrganisationContact
     */
    public function organisationContact()
    {
        return $this->belongsTo(OrganisationContact::class);
    }
}
