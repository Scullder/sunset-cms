<?php

namespace App\Attrs;

#[\Attribute(Attribute::TARGET_PROPERTY)]
class DBAttr
{
    public function __construct(
        public string $type,
        public ?int $length = null,
        public bool $nullable = false,
        public mixed $default = null
    ) {
    }
}
