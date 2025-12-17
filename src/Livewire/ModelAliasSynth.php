<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Livewire;

use Livewire\Features\SupportModels\ModelSynth;
use Sindyko\Aliaser\Registers\ModelRegistry;

class ModelAliasSynth extends ModelSynth
{
    public static $key = 'mdl-alias';

    public static function match($target): bool
    {
        if (! parent::match($target)) {
            return false;
        }

        $class = get_class($target);

        return ModelRegistry::aliasForClass($class) !== null;
    }

    public function dehydrate($target): array
    {
        [$value, $meta] = parent::dehydrate($target);

        $class = get_class($target);
        $alias = ModelRegistry::aliasForClass($class);

        if ($alias) {
            $meta['class'] = $alias;
        }

        return [$value, $meta];
    }

    public function hydrate($data, $meta)
    {
        $alias = $meta['class'] ?? null;

        if ($alias) {
            if ($class = ModelRegistry::find($alias)) {
                $meta['class'] = $class;
            }
        }

        return parent::hydrate($data, $meta);
    }
}
