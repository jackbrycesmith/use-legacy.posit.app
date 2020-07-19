<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasUuid;
use App\Models\StripeAccount;
use CloudCreativity\LaravelStripe\Connect\OwnsStripeAccounts;
use CloudCreativity\LaravelStripe\Contracts\Connect\AccountOwnerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organisation extends Model implements AccountOwnerInterface
{
    use HasUuid, OwnsStripeAccounts;

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

    /**
     * All stripe accounts
     *
     * @return HasMany The has many relationship.
     */
    public function stripeAccounts(): HasMany
    {
        return $this->hasMany(
            StripeAccount::class,
            'owner_id',
            $this->getStripeIdentifierName()
        );
    }

    /**
     * The most recent stripe account
     *
     * @return HasOne The has one relationship.
     */
    public function stripeAccount(): HasOne
    {
        return $this->hasOne(
            StripeAccount::class,
            'owner_id',
            $this->getStripeIdentifierName()
        )->latest();
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
