<?php

declare(strict_types=1);

use Sindyko\Aliaser\Registers\CollectionRegistry;
use Sindyko\Aliaser\Registers\EnumRegistry;
use Sindyko\Aliaser\Registers\FormRegistry;
use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Registers\ObjectRegistry;

if (! function_exists('modelsMap')) {
    function modelsMap(array $map, bool $overwrite = false): void
    {
        ModelRegistry::map($map, $overwrite);
    }
}

if (! function_exists('formsMap')) {
    function formsMap(array $map, bool $overwrite = false): void
    {
        FormRegistry::map($map, $overwrite);
    }
}

if (! function_exists('collectionsMap')) {
    function collectionsMap(array $map, bool $overwrite = false): void
    {
        CollectionRegistry::map($map, $overwrite);
    }
}

if (! function_exists('objectsMap')) {
    function objectsMap(array $map, bool $overwrite = false): void
    {
        ObjectRegistry::map($map, $overwrite);
    }
}

if (! function_exists('enumsMap')) {
    function enumsMap(array $map, bool $overwrite = false): void
    {
        EnumRegistry::map($map, $overwrite);
    }
}
