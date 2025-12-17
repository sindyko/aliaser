<?php

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
        if (! $this->isLivewireAvailable()) {
            return;
        }

        $this->registerSynthesizers();
    }

    protected function isLivewireAvailable(): bool
    {
        return class_exists(\Livewire\Livewire::class);
    }

    protected function registerSynthesizers(): void
    {
        foreach ($this->synthesizers as $synthesizer) {
            if (class_exists($synthesizer)) {
                \Livewire\Livewire::propertySynthesizer($synthesizer);
            }
        }
    }
}
