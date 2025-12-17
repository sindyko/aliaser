<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Orchestra\Testbench\TestCase as Orchestra;
use Sindyko\Aliaser\Providers\AliaserServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->clearRegistries();

        // Очищаем morph map
        Relation::morphMap([], false);
    }

    protected function getPackageProviders($app): array
    {
        return [
            AliaserServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Entity' => \Sindyko\Aliaser\Facades\Entity::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);

        config()->set('aliaser.use_morph_map', true);
        config()->set('aliaser.allow_overwrite', false);
    }

    protected function clearRegistries(): void
    {
        \Sindyko\Aliaser\Registers\ModelRegistry::clear();
        \Sindyko\Aliaser\Registers\FormRegistry::clear();
        \Sindyko\Aliaser\Registers\ObjectRegistry::clear();
        \Sindyko\Aliaser\Registers\CollectionRegistry::clear();
        \Sindyko\Aliaser\Registers\EnumRegistry::clear();
    }
}
