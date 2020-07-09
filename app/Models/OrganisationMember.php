<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Staudenmeir\EloquentHasManyDeep\HasTableAlias;

class OrganisationMember extends Pivot
{
    use HasTableAlias;

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
     * Get the related organisation
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Get the related user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the proposals
     */
    public function proposals()
    {
        return $this->belongsToMany(Proposal::class, 'proposal_user')->withTimestamps();
    }
}
