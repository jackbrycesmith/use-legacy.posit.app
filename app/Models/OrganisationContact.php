<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisationContact extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'organisation_id' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Get the organisation that this contact belongs to
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

}
