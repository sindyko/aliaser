<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Registers;

use Sindyko\Aliaser\Exceptions\AliasNotFoundException;

abstract class AbstractAliasRegistry
{
    protected static bool $allowOverwrite = false;

    abstract protected static function &getStorage(): array;

    public static function map(array $map, bool $overwrite = false): void
    {
        foreach ($map as $alias => $class) {
            static::register($alias, $class, $overwrite);
        }
    }

    public static function register(string $alias, string $class, bool $overwrite = false): void
    {
        $storage = &static::getStorage();
        $reverseStorage = &static::getReverseStorage();

        if (isset($storage[$alias]) && ! $overwrite && ! static::$allowOverwrite) {
            $existingClass = $storage[$alias];

            if ($existingClass === $class) {
                return;
            }

            throw static::createDuplicateException($alias, $existingClass, $class);
        }

        $storage[$alias] = $class;
        $reverseStorage[$class] = $alias;
    }

    public static function allowOverwrite(bool $allow = true): void
    {
        static::$allowOverwrite = $allow;
    }

    public static function classFor(string $alias): string
    {
        $storage = static::getStorage();

        if (! isset($storage[$alias])) {
            throw static::createNotFoundException($alias);
        }

        return $storage[$alias];
    }

    public static function find(string $alias): ?string
    {
        $storage = static::getStorage();

        return $storage[$alias] ?? null;
    }

    public static function getMap(): array
    {
        return static::getStorage();
    }

    public static function clear(): void
    {
        $storage = &static::getStorage();
        $reverseStorage = &static::getReverseStorage();

        $storage = [];
        $reverseStorage = [];
        static::$allowOverwrite = false;
    }

    public static function has(string $alias): bool
    {
        $storage = static::getStorage();

        return isset($storage[$alias]);
    }

    public static function forget(string $alias): void
    {
        $storage = &static::getStorage();
        $reverseStorage = &static::getReverseStorage();

        if (isset($storage[$alias])) {
            $class = $storage[$alias];
            unset($storage[$alias]);
            unset($reverseStorage[$class]);
        }
    }

    public static function aliasForClass(string $class): ?string
    {
        $reverseStorage = static::getReverseStorage();

        return $reverseStorage[$class] ?? null;
    }

    abstract protected static function &getReverseStorage(): array;

    abstract protected static function createDuplicateException(
        string $alias,
        string $existingClass,
        string $newClass
    ): \Exception;

    abstract protected static function createNotFoundException(string $alias): AliasNotFoundException;
}
