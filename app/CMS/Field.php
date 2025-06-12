<?php

namespace App\CMS;

use App\CMS\BaseComponent;
use App\Traits\PathHasher;

abstract class Field extends BaseComponent
{
    use PathHasher;

    protected mixed $value;
    private static array $pathMap = [];

    private function validateType(mixed $value): void
    {
        if ($value !== null && !$this->isValidType($value)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid type for field %s!', static::class)
            );
        }
    }

    abstract protected function isValidType(mixed $value): bool;

    public function set(mixed $value)
    {
        $this->validateType($this->value);
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }

    public function getInputName(): string
    {
        return Field::registerPath($this->path);
    }
}
