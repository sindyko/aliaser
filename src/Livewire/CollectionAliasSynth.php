<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Livewire;

use Illuminate\Support\Collection;
use Livewire\Mechanisms\HandleComponents\Synthesizers\CollectionSynth;
use Sindyko\Aliaser\Registers\CollectionRegistry;

class CollectionAliasSynth extends CollectionSynth
{
    public static $key = 'clctn-alias';

    public static function match($target): bool
    {
        if (! parent::match($target)) {
            return false;
        }

        $class = get_class($target);

        if ($class === Collection::class) {
            return true;
        }

        return CollectionRegistry::aliasForClass($class) !== null;
    }

    public function dehydrate($target, $dehydrateChild): array
    {
        [$data, $meta] = parent::dehydrate($target, $dehydrateChild);

        $class = $meta['class'] ?? null;

        if ($class === Collection::class) {
            $meta['class'] = CollectionRegistry::getLaravelCollectionAlias();
        } elseif ($class) {
            $alias = CollectionRegistry::aliasForClass($class);

            if ($alias) {
                $meta['class'] = $alias;
            }
        }

        return [$data, $meta];
    }

    public function hydrate($value, $meta, $hydrateChild): mixed
    {
        $alias = $meta['class'] ?? null;

        if ($alias === CollectionRegistry::getLaravelCollectionAlias()) {
            $meta['class'] = Collection::class;
        } elseif ($alias) {
            if ($class = CollectionRegistry::find($alias)) {
                $meta['class'] = $class;
            }
        }

        return parent::hydrate($value, $meta, $hydrateChild);
    }
}
