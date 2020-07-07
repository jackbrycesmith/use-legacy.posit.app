<?php

namespace App\Models;

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
}
