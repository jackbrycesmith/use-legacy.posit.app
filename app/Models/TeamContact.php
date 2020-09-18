<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class TeamContact extends Model
{
    use HasFactory;
    use HasUuid;

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
        'user_id' => 'integer',
        'team_id' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Get the organisation that this contact belongs to
     *
     * @return BelongsTo The belongs to relationship.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the stripe customer.
     *
     * @return MorphOne The morph one relationship.
     */
    public function stripeCustomer(): MorphOne
    {
        return $this->morphOne(StripeCustomer::class, 'model');
    }

    /**
     * Creates an access code.
     *
     * @return string
     */
    public static function createAccessCode(): string
    {
        $length = config('posit-settings.org_contact.access_code_length', 16);
        return Str::random($length);
    }
}
