<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Exceptions;

use Exception;

class AliasNotFoundException extends Exception
{
    public static function forModel(string $alias): self
    {
        return new self(
            "Model alias '{$alias}' not found. ".
            "Register it using: entitiesMap(['{$alias}' => YourModel::class])"
        );
    }

    public static function forClass(string $alias): self
    {
        return new self(
            "Class alias '{$alias}' not found. ".
            "Register it using: classesMap(['{$alias}' => YourClass::class])"
        );
    }
}
