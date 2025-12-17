<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Livewire;

use Livewire\Features\SupportFormObjects\FormObjectSynth;
use Sindyko\Aliaser\Registers\FormRegistry;

class FormAliasSynth extends FormObjectSynth
{
    public static $key = 'frm-alias';

    public static function match($target): bool
    {
        if (! parent::match($target)) {
            return false;
        }

        $class = get_class($target);

        return FormRegistry::aliasForClass($class) !== null;
    }

    public function dehydrate($target, $dehydrateChild): array
    {
        [$data, $meta] = parent::dehydrate($target, $dehydrateChild);

        $class = get_class($target);
        $alias = FormRegistry::aliasForClass($class);

        if ($alias) {
            $meta['class'] = $alias;
        }

        return [$data, $meta];
    }

    public function hydrate($data, $meta, $hydrateChild): mixed
    {
        $alias = $meta['class'] ?? null;

        if ($alias) {
            if ($class = FormRegistry::find($alias)) {
                $meta['class'] = $class;
            }
        }

        return parent::hydrate($data, $meta, $hydrateChild);
    }
}
