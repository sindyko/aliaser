<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Support\EntityManager;

class AliaserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/aliaser.php',
            'aliaser'
        );

        $this->app->singleton('entity.manager', function ($app) {
            return new EntityManager();
        });

        $this->app->singleton('entity.registry', function ($app) {
            return new ModelRegistry();
        });

        $this->app->alias('entity.manager', EntityManager::class);
        $this->app->alias('entity.registry', ModelRegistry::class);

        if ($this->isLivewire3Available()) {
            $this->app->register(LivewireSynthServiceProvider::class);
        }
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/aliaser.php' => config_path('aliaser.php'),
            ], 'aliaser-config');

            $this->commands([
                \Sindyko\Aliaser\Commands\InstallCommand::class,
                \Sindyko\Aliaser\Commands\ListCommand::class,
                \Sindyko\Aliaser\Commands\HelpCommand::class,
            ]);
        }

        if (config('aliaser.use_morph_map', true)) {
            $this->syncMorphMap();
        }
    }

    protected function syncMorphMap(): void
    {
        $map = ModelRegistry::getMap();

        if (! empty($map)) {
            Relation::enforceMorphMap($map);
        }
    }

    protected function isLivewire3Available(): bool
    {
        if (! class_exists(\Livewire\Livewire::class)) {
            return false;
        }

        if (class_exists(\Composer\InstalledVersions::class)) {
            try {
                $version = \Composer\InstalledVersions::getVersion('livewire/livewire');

                if ($version && version_compare($version, '3.0.0', '>=')) {
                    return true;
                }
            } catch (\Exception $e) {
                // Fallback
            }
        }

        return class_exists(\Livewire\Mechanisms\HandleComponents\Synthesizers\Synth::class);
    }
}
