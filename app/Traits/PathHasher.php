<?php

namespace App\Traits;

//use App\CMS\Field;

trait PathHasher
{
    private static array $pathMap = [];

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
