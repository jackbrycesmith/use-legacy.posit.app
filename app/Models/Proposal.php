<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasStripeCheckoutSession;
use App\Models\Concerns\HasUuid;
use App\Models\Concerns\HasVideo;
use App\Models\Media;
use App\Models\TeamContact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStatus\HasStatuses;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Proposal extends Model implements HasMedia
{
    use HasFactory;
    use HasUuid;
    use HasRelationships;
    use HasVideo;
    use HasStatuses;
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
        'organisation_id' => 'integer',
        'meta' => 'array',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'name' => 'Proposal',
        'value_currency_code' => config('posit-settings.proposal.value_currency_system_default')
    ];

    const THEME_COOL_GREY = 'cool_grey';
    const ALLOWED_THEMES = [
        self::THEME_COOL_GREY
    ];

    const STATUS_DRAFT = 'proposal_draft';
    const STATUS_PUBLISHED = 'proposal_published';
    const STATUS_ACCEPTED = 'proposal_accepted';
    const STATUS_EXPIRED = 'proposal_expired';
    const STATUS_VOID = 'proposal_void';
    const ALLOWED_STATUSES = [
        self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ACCEPTED, self::STATUS_EXPIRED, self::STATUS_VOID
    ];
    const ALLOWED_VALUE_CURRENCIES = [
        'GBP', 'USD', 'EUR'
    ];
    const PUBLIC_ACCESS_AUTH_REQUIRED_STATUSES = [
        self::STATUS_PUBLISHED, self::STATUS_ACCEPTED
    ];

    const PUBLIC_ACCESS_AUTH_BYPASS_STATUSES = [
        self::STATUS_DRAFT, self::STATUS_EXPIRED, self::STATUS_VOID
    ];

    /**
     * Determines if valid status.
     *
     * @param string $name The name
     * @param null|string $reason The reason
     *
     * @return boolean True if valid status, False otherwise.
     */
    public function isValidStatus(string $name, ?string $reason = null): bool
    {
        // TODO potentially more checks; e.g. if its gone past a particular status, it cannot go back to previous
        return in_array($name, self::ALLOWED_STATUSES);
    }

    public function requiresLiteResource(): bool
    {
        return in_array($this->status, self::PUBLIC_ACCESS_AUTH_BYPASS_STATUSES);
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
        return $this->belongsToMany(TeamContact::class, 'proposal_recipient')->withTimestamps();
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
            $this->proposalRecipients(),
            (new ProposalRecipient)->teamContact()
        )->latest();
    }

    /**
     * Get the proposal users
     *
     * @return HasMany The has many relation.
     */
    public function proposalUsers(): HasMany
    {
        return $this->hasMany(ProposalUser::class);
    }

    /**
     * Get the proposal recipients
     *
     * @return HasMany The has many relation.
     */
    public function proposalRecipients(): HasMany
    {
        return $this->hasMany(ProposalRecipient::class);
    }

    /**
     * Get all the proposal contents
     *
     * @return HasMany The has many relation.
     */
    public function proposalContents(): HasMany
    {
        return $this->hasMany(ProposalContent::class);
    }

    /**
     * Get the proposal content
     *
     * @return HasOne The has one relation.
     */
    public function proposalContent(): HasOne
    {
        // TODO this will need to be removed/refactored...
        return $this->hasOne(ProposalContent::class);
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
            $this->proposalUsers(),
            (new ProposalUser)->teamMember(),
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
     * Get the default proposal theme
     *
     * @return string
     * @todo consider making non static & configurable default for team...
     */
    public static function defaultTheme(): string
    {
        return config('posit-settings.proposal.theme_default', Proposal::THEME_COOL_GREY);
    }
}
