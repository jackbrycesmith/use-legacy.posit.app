<?php

namespace App\Models\Values;

use App\Models\Posit;

class PositConfig extends Value
{
    public function __construct(
        public string $theme,
    ) {}

    public static function defaults(): PositConfig
    {
        return new PositConfig(
            theme: Posit::defaultTheme()
        );
    }
}
