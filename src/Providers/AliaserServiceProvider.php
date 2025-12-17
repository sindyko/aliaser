<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Support\EntityManager;

class AliaserServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../../config/aliaser.php',
            'aliaser'
        );

        // Register EntityManager as singleton
        $this->app->singleton('entity.manager', function ($app) {
            return new EntityManager();
        });

        // Register ModelRegistry as singleton
        $this->app->singleton('entity.registry', function ($app) {
            return new ModelRegistry();
        });

        // Aliases for easier access
        $this->app->alias('entity.manager', EntityManager::class);
        $this->app->alias('entity.registry', ModelRegistry::class);

        // Register Livewire provider if Livewire is installed
        if (class_exists(\Livewire\Livewire::class)) {
            $this->app->register(LivewireSynthServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/aliaser.php' => config_path('aliaser.php'),
            ], 'aliaser-config');

            // Register commands
            $this->commands([
                \Sindyko\Aliaser\Commands\InstallCommand::class,
                \Sindyko\Aliaser\Commands\ListCommand::class,
                \Sindyko\Aliaser\Commands\HelpCommand::class,
            ]);
        }

        // Sync with morph map if enabled
        if (config('aliaser.use_morph_map', true)) {
            $this->syncMorphMap();
        }
    }

    /**
     * Synchronize ModelRegistry aliases with Eloquent morph map.
     */
    protected function syncMorphMap(): void
    {
        $map = ModelRegistry::getMap();

        if (! empty($map)) {
            Relation::enforceMorphMap($map);
        }
    }
}
