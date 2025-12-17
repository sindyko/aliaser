<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Registers;

use Sindyko\Aliaser\Exceptions\AliasNotFoundException;
use Sindyko\Aliaser\Exceptions\DuplicateAliasException;

class ObjectRegistry extends AbstractAliasRegistry
{
    protected static array $map = [];

    protected static array $reverseMap = [];

    protected static function &getStorage(): array
    {
        return static::$map;
    }

    protected static function &getReverseStorage(): array
    {
        return static::$reverseMap;
    }

    protected static function createDuplicateException(
        string $alias,
        string $existingClass,
        string $newClass
    ): \Exception {
        return DuplicateAliasException::forClass($alias, $existingClass, $newClass);
    }

    protected static function createNotFoundException(string $alias): AliasNotFoundException
    {
        return AliasNotFoundException::forClass($alias);
    }
}
