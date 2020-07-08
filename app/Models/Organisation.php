<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Organisation extends Model
{
    use HasUuid;

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
        'name' => StrLimitCast::class,
        'json' => 'array',
    ];

    /**
     * Get the proposals for the organisation.
     */
    public function proposals()
    {
        return $this->hasMany(Proposal::class);
    }

    /**
     * Get the contacts for the organisation.
     */
    public function organisationContacts()
    {
        return $this->hasMany(OrganisationContact::class);
    }

    /**
     * The users that belong to the organisation.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'organisation_member')->withTimestamps();
    }

    // /**
    //  * Get the users for the organisation.
    //  */
    // public function OrganizationUsers()
    // {
    //     return $this->hasMany(OrganisationUser::class);
    // }

    /**
     * Get the users for the organisation.
     */
    // TODO organisation users.
    // public function users()
    // {
    //     return $this->hasMany(OrganisationUser::class);
    // }
}
