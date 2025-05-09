<?php

namespace App\Attrs;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class AdminAttr
{
    public function __construct(
        public string $label,
        public ?string $labelMany = null,
        // public ?string $icon = null,
    ) {
    }
}
