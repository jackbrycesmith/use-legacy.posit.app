<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasInAppCreditBalance;
use App\Models\Concerns\HasLogo;
use App\Models\Concerns\HasUuid;
use CloudCreativity\LaravelStripe\Connect\OwnsStripeAccounts;
use CloudCreativity\LaravelStripe\Contracts\Connect\AccountOwnerInterface;
use Featica\HasFeatureFlags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Laravel\Paddle\Billable as PaddleBillable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Team extends JetstreamTeam implements AccountOwnerInterface, HasMedia
{
    use PaddleBillable;
    use HasFactory;
    use HasLogo {
        HasLogo::registerMediaCollections as protected registerLogoMediaCollections;
    }
    use HasUuid;
    use HasInAppCreditBalance;
    use Notifiable;
    use InteractsWithMedia;
    use OwnsStripeAccounts;
    use HasFeatureFlags;

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
     * Get the posits for the team.
     *
     * @return HasMany The has many relationship.
     */
    public function posits(): HasMany
    {
        return $this->hasMany(Posit::class);
    }

    /**
     * Get the posits that have been published before...
     *
     * @return HasMany The has many relationship.
     */
    public function publishedPosits(): HasMany
    {
        return $this->posits()->haveBeenPublished();
    }

    /**
     * Get the contacts for the team.
     *
     * @return HasMany The has many relationship.
     */
    public function contacts(): HasMany
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

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->owner->email;
    }
}
