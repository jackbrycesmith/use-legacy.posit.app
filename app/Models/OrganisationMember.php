<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganisationMember extends Pivot
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
        'user_id' => 'integer',
        'organisation_id' => 'integer',
    ];

    /**
     * Get the organisation
     *
     * @return BelongsTo The belongs to relation.
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the user
     *
     * @return BelongsTo The belongs to relation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the proposals
     *
     * @return BelongsToMany The belongs to many relation.
     */
    public function proposals(): BelongsToMany
    {
        return $this->belongsToMany(
            Proposal::class,
            'proposal_user',
            'organisation_member_id',
            'proposal_id'
        );
    }
}
