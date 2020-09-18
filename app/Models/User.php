<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use HasUuid;
    use HasRelationships;
    use Notifiable;
    use TwoFactorAuthenticatable;

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
        'name' => StrLimitCast::class,
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Sets the password attribute.
     *
     * @param string $password The password
     *
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    /**
     * Get the organisations this user has access to
     *
     * @return BelongsToMany The belongs to many relation.
     */
    public function organisations(): BelongsToMany
    {
        return $this->belongsToMany(Organisation::class, 'organisation_member')->withTimestamps();
    }

    /**
     * Get the organisation members
     *
     * @return HasMany The has many relation.
     */
    public function organisationMembers(): HasMany
    {
        return $this->hasMany(OrganisationMember::class);
    }

    /**
     * Get the proposals the user can access across all organisations
     *
     * @return HasManyDeep The has many deep relation.
     */
    public function proposals(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->organisationMembers(), (new OrganisationMember)->proposals()
        );
    }

    /**
     * Determines whether the specified user is member of given organisation.
     *
     * @param Organisation $organisation The organisation
     *
     * @return boolean True if the specified organisation is member of organisation, False otherwise.
     */
    public function isMemberOfOrganisation(Organisation $organisation): bool
    {
        return $this->organisations()->where('organisation_id', $organisation->id)->exists();
    }

    // TODO a separate proposals thing if you happen to be a user on the receiving end?
    // Because then it potentially makes it more interesting as a platform; i.e. you download the app/or whatever to store your encrypted keys; might as well create an account at that point. Alhtough a different onboarding process would be needed, until you become a 'full blown member'
}
