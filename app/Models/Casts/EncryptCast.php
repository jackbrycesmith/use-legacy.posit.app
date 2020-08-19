<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EncryptCast implements CastsAttributes
{
    public function __construct(bool $serialize = true)
    {
        $this->serialize = $serialize;
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return ! is_null($value) ? decrypt($value, $this->serialize) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return [$key => ! is_null($value) ? encrypt($value, $this->serialize) : null];
    }
}
