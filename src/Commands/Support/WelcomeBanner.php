<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands\Support;

use Illuminate\Console\Command;

class WelcomeBanner
{
    public static function render(Command $command): void
    {
        $command->newLine();

        $command->line('  <fg=blue>    _    _ _                     </>');
        $command->line('  <fg=blue>   / \\  | (_) __ _ ___  ___ _ __ </>');
        $command->line('  <fg=cyan>  / _ \\ | | |/ _` / __|/ _ \\ \'__|</>');
        $command->line('  <fg=cyan> / ___ \\| | | (_| \\__ \\  __/ |   </>');
        $command->line('  <fg=white>/_/   \\_\\_|_|\\__,_|___/\\___|_|   </>');

        $command->newLine();
        $command->line('  <fg=gray>Beautiful alias management for Laravel</fg=gray>');
        $command->newLine();
    }
}
