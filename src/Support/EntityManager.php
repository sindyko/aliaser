<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Support;

use Sindyko\Aliaser\Registers\ModelRegistry;

class EntityManager
{
    public function __call(string $alias, array $args)
    {
        try {
            $proxy = ModelRegistry::proxy($alias);
        } catch (\Exception $e) {
            throw new \BadMethodCallException(
                "Model alias [{$alias}] not found.",
                0,
                $e
            );
        }

        if (empty($args)) {
            return $proxy;
        }

        return $proxy->find($args[0], $args[1] ?? ['*']);
    }
}
