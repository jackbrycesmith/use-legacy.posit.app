<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalContent extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'proposal_id' => 'integer',
        'proposal_user_id' => 'integer',
        'is_encrypted' => 'boolean',
        'is_published' => 'boolean',
        'content' => 'array',
        'revisions' => 'array',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
