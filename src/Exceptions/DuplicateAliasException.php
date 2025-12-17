<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Exceptions;

use Exception;

class DuplicateAliasException extends Exception
{
    public static function forModel(string $alias, string $existingClass, string $newClass): self
    {
        return new self(
            "Model alias '{$alias}' is already registered for '{$existingClass}'. ".
            "Cannot register it for '{$newClass}'. ".
            'Use a different alias or remove the existing registration.'
        );
    }

    public static function forClass(string $alias, string $existingClass, string $newClass): self
    {
        return new self(
            "Class alias '{$alias}' is already registered for '{$existingClass}'. ".
            "Cannot register it for '{$newClass}'. ".
            'Use a different alias or remove the existing registration.'
        );
    }
}
