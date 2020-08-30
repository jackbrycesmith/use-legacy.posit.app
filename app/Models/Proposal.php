<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasStripeCheckoutSession;
use App\Models\Concerns\HasUuid;
use App\Models\OrganisationContact;
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
    use HasUuid, HasRelationships, HasStatuses, HasStripeCheckoutSession, InteractsWithMedia;

    const INTRO_VIDEO_COLLECTION = 'intro_video';

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
    ];

    const STATUS_DRAFT = 'proposal_draft';
    const STATUS_PUBLISHED = 'proposal_published';
    const STATUS_ACCEPTED = 'proposal_accepted';
    const STATUS_EXPIRED = 'proposal_expired';
    const STATUS_VOID = 'proposal_void';
    const ALLOWED_STATUSES = [
        self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ACCEPTED, self::STATUS_EXPIRED, self::STATUS_VOID
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
     * Get the organisation that this proposal is created under
     *
     * @return BelongsTo The belongs to relation.
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    /**
     * Recipients (organisation contacts)
     *
     * @return BelongsToMany The belongs to many relation.
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(OrganisationContact::class, 'proposal_user')->withTimestamps();
    }

    /**
     * Get the recipient
     *
     * @return HasOneDeep The has one deep relation.
     */
    public function recipient(): HasOneDeep
    {
        return $this->hasOneDeepFromRelations(
            $this->proposalUsers(),
            (new ProposalUser)->organisationContact()
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
            $this->organisation(),
            (new Organisation)->stripeAccount()
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
            (new ProposalUser)->organisationMember(),
            (new OrganisationMember)->user()
        );
    }

    /**
     * Retrieve the organisation contact recipient for the given access code.
     *
     * @param string $accessCode The access code
     *
     * @return OrganisationContact|null
     */
    public function recipientForAccessCode(string $accessCode): ?OrganisationContact
    {
        return $this->recipients()->where(function ($query) use ($accessCode) {
            $query->where('access_code', $accessCode);
        })->first();
    }
}
