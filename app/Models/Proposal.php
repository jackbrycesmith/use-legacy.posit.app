<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;
use App\Models\Concerns\HasStripeCheckoutSession;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasOneDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Proposal extends Model
{
    use HasUuid, HasRelationships, HasStripeCheckoutSession;

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
     * @return <type> ( description_of_the_return_value )
     */
    public function recipients()
    {
        // TODO
        return $this->belongsToMany(OrganisationContact::class, 'proposal_user')->withTimestamps();
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
}
