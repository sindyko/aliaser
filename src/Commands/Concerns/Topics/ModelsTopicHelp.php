<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands\Concerns\Topics;

trait ModelsTopicHelp
{
    protected function showModelsHelp(): int
    {
        $this->newLine();
        $this->components->info('Model Aliases');
        $this->newLine();

        $this->line('  <fg=gray>Register model aliases for Morph Maps and Entity facade:</fg=gray>');
        $this->newLine();

        $this->line('  <fg=yellow>// In AppServiceProvider::boot()</fg=yellow>');
        $this->line('  <fg=white>modelsMap([</>');
        $this->line('      <fg=green>\'user\'</> => <fg=cyan>User::class</>,');
        $this->line('      <fg=green>\'post\'</> => <fg=cyan>Post::class</>,');
        $this->line('  <fg=white>]);</>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Usage with Entity facade</>', '');
        $this->newLine();

        $this->line('  <fg=gray>// Find by ID</>');
        $this->line('  <fg=white>$user = Entity::user(1);</>');
        $this->newLine();

        $this->line('  <fg=gray>// Query builder</>');
        $this->line('  <fg=white>$posts = Entity::post()->where(\'active\', true)->get();</>');
        $this->newLine();

        $this->line('  <fg=gray>// Create new model</>');
        $this->line('  <fg=white>$user = Entity::user()->create([...]);</>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Benefits</>', '');
        $this->newLine();

        $this->line('  <fg=green>✓</> Shorter morph map types in database');
        $this->line('  <fg=green>✓</> Cleaner Livewire snapshots');
        $this->line('  <fg=green>✓</> Convenient Entity facade access');
        $this->line('  <fg=green>✓</> Easier refactoring (change class without DB migration)');
        $this->newLine();

        return self::SUCCESS;
    }
}
