<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable
{
    use Notifiable, HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the organisations this user has access to
     */
    public function organisations()
    {
        return $this->belongsToMany(Organisation::class, 'organisation_member')->withTimestamps();
    }

    /**
     * Get the proposals that this user has access to across all organisations
     */
    public function proposals()
    {
        // TODO this is wrong
        return $this->hasManyDeep(Proposal::class, [
            OrganisationMember::class,
            ProposalUser::class
        ]);
    }
}
