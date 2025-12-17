<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Livewire;

use Livewire\Mechanisms\HandleComponents\Synthesizers\EnumSynth;
use Sindyko\Aliaser\Registers\EnumRegistry;

class EnumAliasSynth extends EnumSynth
{
    public static $key = 'enm-alias';

    public static function match($target): bool
    {
        if (! parent::match($target)) {
            return false;
        }

        $class = get_class($target);

        return EnumRegistry::aliasForClass($class) !== null;
    }

    public function dehydrate($target): array
    {
        [$value, $meta] = parent::dehydrate($target);

        $class = $meta['class'] ?? null;

        if ($class) {
            $alias = EnumRegistry::aliasForClass($class);

            if ($alias) {
                $meta['class'] = $alias;
            }
        }

        return [$value, $meta];
    }

    public function hydrate($value, $meta): mixed
    {
        $alias = $meta['class'] ?? null;

        if ($alias) {
            $class = EnumRegistry::find($alias);

            if ($class) {
                $meta['class'] = $class;
            }
        }

        return parent::hydrate($value, $meta);
    }
}
