<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Registers;

use Sindyko\Aliaser\Exceptions\AliasNotFoundException;
use Sindyko\Aliaser\Exceptions\DuplicateAliasException;

class CollectionRegistry extends AbstractAliasRegistry
{
    public const LARAVEL_COLLECTION_ALIAS = 'lrvl_clctn';

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

    public static function getLaravelCollectionAlias(): string
    {
        return static::LARAVEL_COLLECTION_ALIAS;
    }

    public static function isReserved(string $alias): bool
    {
        return $alias === static::LARAVEL_COLLECTION_ALIAS;
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
