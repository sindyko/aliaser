<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'aliaser:install
                            {--force : Overwrite existing config file}';

    protected $description = 'Install Aliaser package configuration';

    public function handle(): int
    {
        $configExists = file_exists(config_path('aliaser.php'));

        if ($configExists && ! $this->option('force')) {
            $this->components->info('Aliaser is already installed!');
            $this->newLine();

            if ($this->confirm('Show usage instructions?', true)) {
                $this->showInstructions();
            }

            $this->newLine();
            $this->line('Tip: Use <comment>--force</comment> to overwrite config file');
            $this->line('Tip: Run <comment>php artisan aliaser:help</comment> for detailed help');

            return self::SUCCESS;
        }

        $this->components->info('Installing Aliaser...');

        $params = [
            '--provider' => 'Sindyko\Aliaser\Providers\AliaserServiceProvider',
            '--tag' => 'aliaser-config',
        ];

        if ($this->option('force')) {
            $params['--force'] = true;
        }

        $this->call('vendor:publish', $params);

        $this->newLine();
        $this->components->info('Aliaser installed successfully!');
        $this->newLine();

        $this->showInstructions();

        return self::SUCCESS;
    }

    protected function showInstructions(): void
    {
        $this->line('Next steps:');
        $this->newLine();

        $this->line('  <fg=gray>// In AppServiceProvider::boot()</fg=gray>');
        $this->newLine();

        $this->line('  <comment>1. Register model aliases:</comment>');
        $this->line('     <fg=gray>modelsMap([</fg=gray>');
        $this->line('         <fg=green>\'user\'</fg=green> => <fg=cyan>User::class</fg=cyan>,');
        $this->line('         <fg=green>\'post\'</fg=green> => <fg=cyan>Post::class</fg=cyan>,');
        $this->line('     <fg=gray>]);</fg=gray>');
        $this->newLine();

        $this->line('  <comment>2. Register form aliases (Livewire):</comment>');
        $this->line('     <fg=gray>formsMap([</fg=gray>');
        $this->line('         <fg=green>\'postForm\'</fg=green> => <fg=cyan>PostForm::class</fg=cyan>,');
        $this->line('     <fg=gray>]);</fg=gray>');
        $this->newLine();

        $this->line('  <comment>3. Register object aliases (DTOs, Value Objects):</comment>');
        $this->line('     <fg=gray>objectsMap([</fg=gray>');
        $this->line('         <fg=green>\'money\'</fg=green> => <fg=cyan>Money::class</fg=cyan>,');
        $this->line('     <fg=gray>]);</fg=gray>');
        $this->newLine();

        $this->line('  <comment>4. Use Entity facade:</comment>');
        $this->line('     <fg=gray>$user = <fg=cyan>Entity</fg=cyan>::<fg=green>user</fg=green>(1);</fg=gray>');
        $this->line('     <fg=gray>$posts = <fg=cyan>Entity</fg=cyan>::<fg=green>post</fg=green>()->get();</fg=gray>');
        $this->newLine();

        $this->line('  <comment>5. View registered aliases:</comment>');
        $this->line('     <fg=gray>php artisan aliaser:list</fg=gray>');
    }
}
