<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Jetstream\Membership as JetstreamMembership;

class TeamMember extends JetstreamMembership
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
        'team_id' => 'integer',
    ];

    /**
     * Get the team
     *
     * @return BelongsTo The belongs to relation.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
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
            'team_user_id',
            'proposal_id'
        );
    }
}
