<?php

namespace App\Models\Values;

use App\Models\Casts\ValueCast;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;
use function Safe\json_encode;
use function Safe\json_decode;

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
        return new ValueCast(static::class);
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

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromJson($json)
    {
        $data = json_decode($json, true);
        return new static(...$data);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }
}
