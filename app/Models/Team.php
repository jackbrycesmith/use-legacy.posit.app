<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasInAppCreditBalance;
use App\Models\Concerns\HasLogo;
use App\Models\Concerns\HasUuid;
use CloudCreativity\LaravelStripe\Connect\OwnsStripeAccounts;
use CloudCreativity\LaravelStripe\Contracts\Connect\AccountOwnerInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Team extends JetstreamTeam implements AccountOwnerInterface, HasMedia
{
    use HasFactory;
    use HasLogo {
        registerMediaCollections as protected registerLogoMediaCollections;
    }
    use HasUuid;
    use HasInAppCreditBalance;
    use InteractsWithMedia;
    use OwnsStripeAccounts;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'personal_team' => 'boolean',
        'name' => StrLimitCast::class,
        'json' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];


    /**
     * Get the proposals for the team.
     *
     * @return HasMany The has many relationship.
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    /**
     * Get the proposals that have been published before...
     *
     * @return HasMany The has many relationship.
     */
    public function publishedProposals(): HasMany
    {
        return $this->proposals()->currentStatus([
            Proposal::STATUS_PUBLISHED,
            Proposal::STATUS_ACCEPTED,
            Proposal::STATUS_EXPIRED
        ]);
    }

    /**
     * Get the contacts for the team.
     */
    public function contacts()
    {
        return $this->hasMany(TeamContact::class);
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

    /**
     * Register any media collections & associated conversions.
     *
     * @see https://spatie.be/docs/laravel-medialibrary/v8/working-with-media-collections/defining-media-collections
     */
    public function registerMediaCollections(): void
    {
        $this->registerLogoMediaCollections();
    }
}
