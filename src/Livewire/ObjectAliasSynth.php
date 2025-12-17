<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Livewire;

use Livewire\Mechanisms\HandleComponents\Synthesizers\Synth;
use Sindyko\Aliaser\Livewire\Concerns\ExcludesStandardTypes;
use Sindyko\Aliaser\Registers\ObjectRegistry;

class ObjectAliasSynth extends Synth
{
    use ExcludesStandardTypes;

    public static $key = 'obj-alias';

    public static function match($target): bool
    {
        if (! static::isNotStandardType($target)) {
            return false;
        }

        $class = get_class($target);

        return ObjectRegistry::aliasForClass($class) !== null;
    }

    public function dehydrate($target, $dehydrateChild): array
    {
        $class = get_class($target);

        $alias = ObjectRegistry::aliasForClass($class);

        // Fallback на полный путь класса
        if (! $alias) {
            $alias = $class;
        }

        $data = $this->extractData($target);

        return [
            $data,
            ['class' => $alias],
        ];
    }

    public function hydrate($value, $meta, $hydrateChild)
    {
        $alias = $meta['class'] ?? null;

        if (! $alias) {
            return null;
        }

        $class = ObjectRegistry::find($alias);

        if (! $class && class_exists($alias)) {
            $class = $alias;
        }

        if (! $class) {
            return null;
        }

        return $this->createInstance($class, $value);
    }

    protected function extractData(object $target): array
    {
        if (method_exists($target, 'toArray')) {
            return $target->toArray();
        }

        $data = [];
        $reflection = new \ReflectionClass($target);

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            $name = $property->getName();

            if ($property->isInitialized($target)) {
                $data[$name] = $property->getValue($target);
            } else {
                $data[$name] = null;
            }
        }

        return $data;
    }

    protected function createInstance(string $class, mixed $value): object
    {
        if (! is_array($value)) {
            return new $class();
        }

        $instance = new $class();

        if (method_exists($instance, 'fill')) {
            return $instance->fill($value);
        }

        foreach ($value as $key => $val) {
            if (property_exists($instance, $key)) {
                $instance->$key = $val;
            }
        }

        return $instance;
    }

    public function get(&$target, $key)
    {
        return $target->{$key} ?? null;
    }

    public function set(&$target, $key, $value): void
    {
        $target->{$key} = $value;
    }
}
