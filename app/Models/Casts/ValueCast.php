<?php

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class ValueCast implements CastsAttributes
{
    public function __construct(
        protected string $valueClass,
    ) {}

    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return;
        }

        return $this->valueClass::fromJson($value);

//        $config = Arr::get($attributes, $key, []);
//        return new $this->valueClass(...$config);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        // TODO perhaps a separate 'nullable' value cast? (at the moment the database could be null...
        if (is_null($value)) {
            return;
        }

        if (is_array($value)) {
            $value = new $this->valueClass(...$value);
        }

        if (! $value instanceof $this->valueClass) {
            throw new InvalidArgumentException("Value must be of type [$this->valueClass], array, or null");
        }

        // TODO ensure the value extends the abstract class Value
        return $value->toJson();
    }
}
