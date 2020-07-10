<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Proposal extends Model
{
    use HasUuid, HasRelationships;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'organisation_id' => 'integer',
        'meta' => 'array',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
