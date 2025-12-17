<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands\Concerns\Topics;

trait LivewireTopicHelp
{
    protected function showLivewireHelp(): int
    {
        $this->newLine();
        $this->components->info('Livewire Integration');
        $this->newLine();

        $this->line('  <fg=gray>Aliaser automatically integrates with Livewire to mask class paths.</fg=gray>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Supported types</>', '');
        $this->newLine();

        $this->line('  <fg=green>✓</> Models');
        $this->line('  <fg=green>✓</> Eloquent Collections');
        $this->line('  <fg=green>✓</> Support Collections');
        $this->line('  <fg=green>✓</> Livewire Forms');
        $this->line('  <fg=green>✓</> Enums');
        $this->line('  <fg=green>✓</> DTOs / Value Objects');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Snapshot comparison</>', '');
        $this->newLine();

        $this->line('  <fg=red>Without Aliaser:</>');
        $this->line('  {');
        $this->line('    "user": ["mdl", {...}, {"class": "App\\\\Models\\\\User"}],');
        $this->line('    "posts": ["elcln", {...}, {"class": "...", "modelClass": "App\\\\Models\\\\Post"}]');
        $this->line('  }');
        $this->newLine();

        $this->line('  <fg=green>With Aliaser:</>');
        $this->line('  {');
        $this->line('    "user": ["mdl-alias", {...}, {"class": "user"}],');
        $this->line('    "posts": ["elcln-alias", {...}, {"class": "elqn_clctn", "modelClass": "post"}]');
        $this->line('  }');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Benefits</>', '');
        $this->newLine();

        $this->line('  <fg=green>✓</> Reduced HTML size (up to 50% smaller snapshots)');
        $this->line('  <fg=green>✓</> Faster page loads');
        $this->line('  <fg=green>✓</> Better security (obfuscated paths)');
        $this->line('  <fg=green>✓</> Works automatically after registration');
        $this->newLine();

        return self::SUCCESS;
    }
}
