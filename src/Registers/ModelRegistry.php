<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Registers;

use Sindyko\Aliaser\Exceptions\AliasNotFoundException;
use Sindyko\Aliaser\Exceptions\DuplicateAliasException;
use Sindyko\Aliaser\Support\ModelProxy;

class ModelRegistry extends AbstractAliasRegistry
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

    public static function proxy(string $alias): ModelProxy
    {
        return new ModelProxy(static::classFor($alias));
    }

    protected static function createDuplicateException(
        string $alias,
        string $existingClass,
        string $newClass
    ): \Exception {
        return DuplicateAliasException::forModel($alias, $existingClass, $newClass);
    }

    protected static function createNotFoundException(string $alias): AliasNotFoundException
    {
        return AliasNotFoundException::forModel($alias);
    }
}
