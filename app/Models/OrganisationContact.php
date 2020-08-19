<?php

namespace App\Models;

use App\Models\Casts\EncryptCast;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class OrganisationContact extends Model
{
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
        'organisation_id' => 'integer',
        'meta' => 'array',
        'access_code' => EncryptCast::class.':0'
    ];

    /**
     * Get the organisation that this contact belongs to
     *
     * @return BelongsTo The belongs to relationship.
     */
    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
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
