<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Support\Str;

class StrLimitCast implements CastsInboundAttributes
{
    public function __construct($length = 255)
    {
        $this->length = $length;
    }

    public function set($model, $key, $value, $attributes)
    {
        return [$key => Str::limit((string) $value, $this->length)];
    }
}
