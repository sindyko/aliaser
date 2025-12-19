<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Providers;

use Illuminate\Support\ServiceProvider;
use Sindyko\Aliaser\Livewire\CollectionAliasSynth;
use Sindyko\Aliaser\Livewire\EloquentCollectionAliasSynth;
use Sindyko\Aliaser\Livewire\EnumAliasSynth;
use Sindyko\Aliaser\Livewire\FormAliasSynth;
use Sindyko\Aliaser\Livewire\ModelAliasSynth;
use Sindyko\Aliaser\Livewire\ObjectAliasSynth;

class LivewireSynthServiceProvider extends ServiceProvider
{
    protected array $synthesizers = [
        ModelAliasSynth::class,
        EloquentCollectionAliasSynth::class,
        CollectionAliasSynth::class,
        FormAliasSynth::class,
        EnumAliasSynth::class,
        ObjectAliasSynth::class,
    ];

    public function boot(): void
    {
        if (! $this->isLivewireV3OrHigher()) {
            return;
        }

        $this->registerSynthesizers();
    }

    protected function isLivewireV3OrHigher(): bool
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

    protected function registerSynthesizers(): void
    {
        foreach ($this->synthesizers as $synthesizer) {
            \Livewire\Livewire::propertySynthesizer($synthesizer);
        }
    }
}
