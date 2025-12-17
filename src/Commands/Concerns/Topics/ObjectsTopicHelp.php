<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands\Concerns\Topics;

trait ObjectsTopicHelp
{
    protected function showObjectsHelp(): int
    {
        $this->newLine();
        $this->components->info('Object Aliases (DTOs, Value Objects)');
        $this->newLine();

        $this->line('  <fg=gray>Register plain PHP object aliases:</fg=gray>');
        $this->newLine();

        $this->line('  <fg=yellow>// In AppServiceProvider::boot()</fg=yellow>');
        $this->line('  <fg=white>objectsMap([</>');
        $this->line('      <fg=green>\'money\'</> => <fg=cyan>Money::class</>,');
        $this->line('      <fg=green>\'userFilter\'</> => <fg=cyan>UserFilterDTO::class</>,');
        $this->line('      <fg=green>\'address\'</> => <fg=cyan>Address::class</>,');
        $this->line('  <fg=white>]);</>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Usage in Livewire</>', '');
        $this->newLine();

        $this->line('  <fg=white>class ProductComponent extends Component</>');
        $this->line('  <fg=white>{</>');
        $this->line('      <fg=cyan>public Money</> <fg=white>$price;</>');
        $this->line('      <fg=cyan>public Address</> <fg=white>$shipping;</>');
        $this->line('  <fg=white>}</>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Snapshot</>', '');
        $this->newLine();

        $this->line('  <fg=green>✓</> "price": ["obj-alias", {...}, {"class": "money"}]');
        $this->line('  <fg=red>✗</> "price": ["obj", {...}, {"class": "App\\\\ValueObjects\\\\Money"}]');
        $this->newLine();

        return self::SUCCESS;
    }
}
