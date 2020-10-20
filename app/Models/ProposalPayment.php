<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalPayment extends Model
{
    use HasFactory;
    use HasUuid;

    const TYPE_DEPOSIT = 'deposit';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the proposal
     *
     * @return BelongsTo The belongs to relation.
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Scope a query to only include deposit type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeposit($query)
    {
        return $query->where('type', '=', self::TYPE_DEPOSIT);
    }
}
