<?php

namespace App\Models\Values;

class PositContent extends Value
{
    public function __construct(
        public string $type = 'document',
        public mixed $content = null
    ) {}
}
