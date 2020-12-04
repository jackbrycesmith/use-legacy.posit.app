<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PositUser extends Pivot
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
        'posit_id' => 'integer',
        'team_user_id' => 'integer',
    ];

    /**
     * Get the proposal
     *
     * @return BelongsTo The belongs to relation.
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Posit::class);
    }

    /**
     * Get the team member
     *
     * @return BelongsTo The belongs to relation.
     */
    public function teamMember(): BelongsTo
    {
        return $this->belongsTo(TeamMember::class);
    }
}
