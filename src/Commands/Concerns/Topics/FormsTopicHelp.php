<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands\Concerns\Topics;

trait FormsTopicHelp
{
    protected function showFormsHelp(): int
    {
        $this->newLine();
        $this->components->info('Livewire Form Aliases');
        $this->newLine();

        $this->line('  <fg=gray>Register Livewire form aliases for shorter snapshots:</fg=gray>');
        $this->newLine();

        $this->line('  <fg=yellow>// In AppServiceProvider::boot()</fg=yellow>');
        $this->line('  <fg=white>formsMap([</>');
        $this->line('      <fg=green>\'postForm\'</> => <fg=cyan>PostForm::class</>,');
        $this->line('      <fg=green>\'userForm\'</> => <fg=cyan>UserForm::class</>,');
        $this->line('  <fg=white>]);</>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Snapshot comparison</>', '');
        $this->newLine();

        $this->line('  <fg=red>Before:</> "class": "App\\\\Livewire\\\\Forms\\\\PostForm"');
        $this->line('  <fg=green>After:</>  "class": "postForm"');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Benefits</>', '');
        $this->newLine();

        $this->line('  <fg=green>✓</> Reduced snapshot size (faster page loads)');
        $this->line('  <fg=green>✓</> Obfuscated class paths (better security)');
        $this->line('  <fg=green>✓</> Easier to read in browser DevTools');
        $this->newLine();

        return self::SUCCESS;
    }
}
