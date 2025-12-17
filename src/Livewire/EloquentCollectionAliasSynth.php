<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Livewire;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Livewire\Features\SupportModels\EloquentCollectionSynth;
use Sindyko\Aliaser\Registers\ModelRegistry;

class EloquentCollectionAliasSynth extends EloquentCollectionSynth
{
    public const ELOQUENT_COLLECTION_ALIAS = 'elqn_clctn';

    public static $key = 'elcln-alias';

    public static function match($target): bool
    {
        if (! parent::match($target)) {
            return false;
        }

        $class = get_class($target);

        if ($class === EloquentCollection::class) {
            return true;
        }

        if ($target->isEmpty()) {
            return false;
        }

        $modelClass = $target->getQueueableClass();

        if (! $modelClass) {
            return false;
        }

        return ModelRegistry::aliasForClass($modelClass) !== null;
    }

    public function dehydrate(EloquentCollection $target, $dehydrateChild): array
    {
        [$data, $meta] = parent::dehydrate($target, $dehydrateChild);

        $collectionClass = $meta['class'] ?? null;
        $modelClass = $meta['modelClass'] ?? null;

        if ($collectionClass === EloquentCollection::class) {
            $meta['class'] = static::ELOQUENT_COLLECTION_ALIAS;
        }

        if ($modelClass) {
            $alias = ModelRegistry::aliasForClass($modelClass);

            if ($alias) {
                $meta['modelClass'] = $alias;
            }
        }

        return [$data, $meta];
    }

    public function hydrate($data, $meta, $hydrateChild): mixed
    {
        $collectionAlias = $meta['class'] ?? null;
        $modelAlias = $meta['modelClass'] ?? null;

        if ($collectionAlias === static::ELOQUENT_COLLECTION_ALIAS) {
            $meta['class'] = EloquentCollection::class;
        }

        if ($modelAlias) {
            if ($class = ModelRegistry::find($modelAlias)) {
                $meta['modelClass'] = $class;
            }
        }

        return parent::hydrate($data, $meta, $hydrateChild);
    }
}
