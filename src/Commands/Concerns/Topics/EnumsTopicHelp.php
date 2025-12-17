<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands\Concerns\Topics;

trait EnumsTopicHelp
{
    protected function showEnumsHelp(): int
    {
        $this->newLine();
        $this->components->info('Enum Aliases');
        $this->newLine();

        $this->line('  <fg=gray>Register enum aliases:</fg=gray>');
        $this->newLine();

        $this->line('  <fg=yellow>// In AppServiceProvider::boot()</fg=yellow>');
        $this->line('  <fg=white>enumsMap([</>');
        $this->line('      <fg=green>\'userStatus\'</> => <fg=cyan>UserStatus::class</>,');
        $this->line('      <fg=green>\'postStatus\'</> => <fg=cyan>PostStatus::class</>,');
        $this->line('  <fg=white>]);</>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Example enum</>', '');
        $this->newLine();

        $this->line('  <fg=white>enum UserStatus: string</>');
        $this->line('  <fg=white>{</>');
        $this->line('      <fg=white>case ACTIVE = \'active\';</>');
        $this->line('      <fg=white>case BANNED = \'banned\';</>');
        $this->line('  <fg=white>}</>');
        $this->newLine();

        $this->components->twoColumnDetail('<fg=cyan>Usage in Livewire</>', '');
        $this->newLine();

        $this->line('  <fg=white>public UserStatus $status = UserStatus::ACTIVE;</>');
        $this->newLine();

        return self::SUCCESS;
    }
}
