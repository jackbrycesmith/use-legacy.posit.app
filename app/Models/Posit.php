<?php

namespace App\Models;

use App\Enums\PositType;
use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasStripeCheckoutSession;
use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasVideo;
use App\Models\Media;
use App\Models\States\Posit\PositState;
use App\Models\TeamContact;
use App\Models\Values\PositConfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Posit extends Model implements HasMedia
{
    use HasFactory;
    use HasUuid;
    use HasRelationships;
    use HasVideo;
    use HasStates;
    use HasStripeCheckoutSession;
    use InteractsWithMedia;

    const INTRO_VIDEO_COLLECTION = 'intro_video';
    const VIDEO_UPLOAD_MIME_TYPES = [
        'video/mp4',
        'video/webm',
        'video/mpeg',
        'video/quicktime',
        'video/x-matroska', // .mkv
        'video/x-msvideo', // .avi
    ];

    public function registerMediaCollections(): void
    {
        // $this->addMediaCollection(self::INTRO_VIDEO_COLLECTION)
        //     // ->acceptsFile(function (File $file) {
        //     //     return $file->mimeType === 'image/jpeg';
        //     // })
        //     ->acceptsMimeTypes(self::VIDEO_UPLOAD_MIME_TYPES)
        //     ->singleFile()
        //     ->useDisk('s3')
        //     ->registerMediaConversions(function (Media $media) {

        //         $this->addMediaConversion('thumb')
        //             ->withoutManipulations()
        //         // $this
        //         //     ->addMediaConversion('thumb')
        //         //     ->width(100)
        //         //     ->height(100);
        //     });
    }

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
        'state' => PositState::class,
        'config' => PositConfig::class,
        'type' => PositType::class,
        'team_id' => 'integer',
        'meta' => 'array',
        'value_amount' => 'float',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        //
    ];

    const THEME_COOL_GREY = 'cool_grey';
    const ALLOWED_THEMES = [
        self::THEME_COOL_GREY
    ];

    const ALLOWED_VALUE_CURRENCIES = [
        'GBP', 'USD', 'EUR', 'AUD', 'CAD', 'NZD'
    ];

    public function requiresLiteResource(): bool
    {
        return $this->state->canBypassPublicAuthAccess();
    }

    /**
     * Get the team that this proposal is created under
     *
     * @return BelongsTo The belongs to relation.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Recipients (team contacts)
     *
     * @return BelongsToMany The belongs to many relation.
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(TeamContact::class, 'posit_recipient')->withTimestamps();
    }

    /**
     * Get the creator
     *
     * @return HasOneDeep The has one deep.
     * @todo   Don't hardcode the creator as the team owner
     */
    public function creator(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->team(),
            (new Team)->owner()
        );
    }

    /**
     * Get the recipient
     * // TODO think this is unnecessary complicated
     *
     * @return HasOneDeep The has one deep relation.
     */
    public function recipient(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->positRecipients(),
            (new PositRecipient)->teamContact()
        )->latest();
    }

    /**
     * Get the proposal users
     *
     * @return HasMany The has many relation.
     */
    public function positUsers(): HasMany
    {
        return $this->hasMany(PositUser::class);
    }

    /**
     * Get the proposal recipients
     *
     * @return HasMany The has many relation.
     */
    public function positRecipients(): HasMany
    {
        return $this->hasMany(PositRecipient::class);
    }

    /**
     * Get all the proposal contents
     *
     * @return HasMany The has many relation.
     */
    public function positContents(): HasMany
    {
        return $this->hasMany(PositContent::class);
    }

    /**
     * Get all the proposal payments
     *
     * @return HasMany The has many relation.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(PositPayment::class);
    }

    /**
     * Get the proposal content
     *
     * @return HasOne The has one relation.
     */
    public function positContent(): HasOne
    {
        // TODO this will need to be removed/refactored...
        return $this->hasOne(PositContent::class);
    }

    /**
     * Get the deposit type
     *
     * @return HasOne The has one relation.
     */
    public function depositPayment(): HasOne
    {
        return $this->hasOne(PositPayment::class)->deposit();
    }

    /**
     * Get the stripe account for this proposal from the organisation.
     * TODO: this may be unnecessary; especially if i have to checks on the
     * org before publishing.
     *
     * @return HasOneDeep The has one deep relation.
     */
    public function stripeAccount(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->team(),
            (new Team)->stripeAccount()
        );
    }

    /**
     * Get the users that are members of the organisation that has provided
     * access to this proposal
     *
     * @return HasManyDeep The has many deep relation.
     */
    public function users(): HasManyDeep
    {
        return $this->hasManyDeepFromRelations(
            $this->positUsers(),
            (new PositUser)->teamMember(),
            (new TeamMember)->user()
        );
    }

    /**
     * Retrieve the organisation contact recipient for the given access code.
     *
     * @param string $accessCode The access code
     *
     * @return TeamContact|null
     */
    public function recipientForAccessCode(string $accessCode): ?TeamContact
    {
        return $this->recipients()->where(function ($query) use ($accessCode) {
            $query->where('access_code', $accessCode);
        })->first();
    }

    /**
     * Scope a query to only include those that have passed through the published state.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHaveBeenPublished($query)
    {
        return $query->whereIn('state', PositState::statesThatHaveBeenPublished());
    }

    /**
     * Get the default proposal theme
     *
     * @return string
     * @todo consider making non static & configurable default for team...
     */
    public static function defaultTheme(): string
    {
        return config('posit-settings.posit.theme_default', Posit::THEME_COOL_GREY);
    }

    /**
     * Get the default proposal value currency
     *
     * @return string
     * @todo consider making non static & configurable default for team...
     */
    public static function defaultValueCurrency(): string
    {
        return config('posit-settings.posit.value_currency_system_default', 'GBP');
    }

    /**
     * Get the default proposal name
     *
     * @return string
     * @todo consider making non static & configurable default for team...
     */
    public static function defaultName(): string
    {
        return config('posit-settings.posit.name_default');
    }
}
