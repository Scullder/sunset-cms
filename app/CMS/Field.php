<?php

namespace App\CMS;

use App\CMS\BaseComponent;

abstract class Field extends BaseComponent
{
    protected $value;
    private static array $pathMap = [];

    public function __construct(mixed $value = null, array $attributes = [])
    {
        parent::__construct($attributes);
        $this->value = $value;
    }

    public function set(mixed $value)
    {
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

    protected static function registerPath(string $path): string
    {
        $hash = 'f_' . substr(md5($path), 0, 8);
        self::$pathMap[$hash] = $path;
        
        return $hash;
    }

    public static function getPathFromHash(string $hash): ?string
    {
        return self::$pathMap[$hash] ?? null;
    }
}