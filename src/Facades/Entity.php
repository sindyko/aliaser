<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Entity facade for convenient model access via aliases.
 *
 * @see \Sindyko\Aliaser\Support\EntityManager
 */
class Entity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'entity.manager';
    }
}
