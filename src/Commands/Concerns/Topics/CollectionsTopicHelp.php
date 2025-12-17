<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands\Concerns\Topics;

trait CollectionsTopicHelp
{
    protected function showCollectionsHelp(): int
    {
        $this->newLine();
        $this->components->info('Collection Aliases');
        $this->newLine();

        $this->line('  <fg=gray>Register custom collection aliases:</fg=gray>');
        $this->newLine();

        $this->line('  <fg=yellow>// In AppServiceProvider::boot()</fg=yellow>');
        $this->line('  <fg=white>collectionsMap([</>');
        $this->line('      <fg=green>\'userCollection\'</> => <fg=cyan>UserCollection::class</>,');
        $this->line('  <fg=white>]);</>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Built-in aliases</>', '');
        $this->newLine();

        $this->line('  <fg=yellow>illuminate_collection</> - <fg=gray>Illuminate\\Support\\Collection</>');
        $this->line('  <fg=yellow>eloquent_collection</> - <fg=gray>Illuminate\\Database\\Eloquent\\Collection</>');
        $this->newLine();

        $this->line('  <fg=gray>These are automatically aliased - no registration needed!</fg=gray>');
        $this->newLine();

        return self::SUCCESS;
    }
}
