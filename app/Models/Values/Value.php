<?php

namespace App\Models\Values;

use App\Models\Casts\ValueCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

abstract class Value implements Castable, JsonSerializable, Arrayable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return CastsAttributes|string
     */
    public static function castUsing(array $arguments): CastsAttributes|string
    {
        return new ValueCast(
            valueClass: static::class,
            encrypted: in_array('encrypted', $arguments)
        );
    }

    public function toArray(): array
    {
        $data = [];

        $class = new ReflectionClass(static::class);

        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $reflectionProperty) {
            // Skip static properties
            if ($reflectionProperty->isStatic()) {
                continue;
            }

            $data[$reflectionProperty->getName()] = $reflectionProperty->getValue($this);
        }

        return $data;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toJson(): string
    {
        return json_encode(value: $this->toArray(), flags: JSON_THROW_ON_ERROR);
    }

    public static function fromJson($json)
    {
        $data = json_decode(json: $json, associative: true, flags: JSON_THROW_ON_ERROR);
        return new static(...$data);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
