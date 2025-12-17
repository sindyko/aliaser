<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Livewire\Concerns;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Form;

trait ExcludesStandardTypes
{
    protected static function isNotStandardType($target): bool
    {
        if (! is_object($target)) {
            return false;
        }

        if ($target instanceof Model || $target instanceof EloquentCollection) {
            return false;
        }

        if ($target instanceof Collection) {
            return false;
        }

        if ($target instanceof Form) {
            return false;
        }

        if ($target instanceof \stdClass ||
            $target instanceof \DateTimeInterface ||
            $target instanceof \Stringable ||
            $target instanceof \BackedEnum
        ) {
            return false;
        }

        return true;
    }
}
