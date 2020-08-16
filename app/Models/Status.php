<?php

namespace App\Models;

use App\Models\Casts\StrLimitCast;

class Status extends \Spatie\ModelStatus\Status
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => StrLimitCast::class,
    ];
}
