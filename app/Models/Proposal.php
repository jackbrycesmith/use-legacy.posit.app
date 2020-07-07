<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasUuid;

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
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }
}
