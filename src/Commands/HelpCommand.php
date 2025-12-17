<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands;

use Illuminate\Console\Command;
use Sindyko\Aliaser\Commands\Concerns\Topics\CollectionsTopicHelp;
use Sindyko\Aliaser\Commands\Concerns\Topics\EnumsTopicHelp;
use Sindyko\Aliaser\Commands\Concerns\Topics\FormsTopicHelp;
use Sindyko\Aliaser\Commands\Concerns\Topics\LivewireTopicHelp;
use Sindyko\Aliaser\Commands\Concerns\Topics\ModelsTopicHelp;
use Sindyko\Aliaser\Commands\Concerns\Topics\ObjectsTopicHelp;
use Sindyko\Aliaser\Commands\Support\WelcomeBanner;

class HelpCommand extends Command
{
    use CollectionsTopicHelp,
        EnumsTopicHelp,
        FormsTopicHelp,
        LivewireTopicHelp,
        ModelsTopicHelp,
        ObjectsTopicHelp;

    protected $signature = 'aliaser:help
                            {topic? : Specific topic (models, forms, objects, collections, enums, livewire)}';

    protected $description = 'Display help for Aliaser package';

    public function handle(): int
    {
        $topic = $this->argument('topic');

        if ($topic) {
            return $this->showTopicHelp($topic);
        }

        return $this->showGeneralHelp();
    }

    protected function showGeneralHelp(): int
    {
        WelcomeBanner::render($this);

        $this->components->info('Available Commands');
        $this->newLine();

        $commands = [
            ['aliaser:install', 'Install Aliaser configuration'],
            ['aliaser:list', 'List all registered aliases'],
            ['aliaser:help [topic]', 'Show detailed help'],
        ];

        foreach ($commands as [$command, $description]) {
            $this->components->twoColumnDetail(
                "<fg=green>{$command}</>",
                "<fg=gray>{$description}</>"
            );
        }

        $this->newLine();
        $this->components->info('Available Topics');
        $this->newLine();

        $topics = [
            ['models', 'Model aliases and Entity facade'],
            ['forms', 'Livewire form aliases'],
            ['objects', 'DTO and Value Object aliases'],
            ['collections', 'Custom collection aliases'],
            ['enums', 'Enum aliases'],
            ['livewire', 'Livewire integration overview'],
        ];

        foreach ($topics as [$topic, $description]) {
            $this->components->twoColumnDetail(
                "<fg=cyan>{$topic}</>",
                "<fg=gray>{$description}</>"
            );
        }

        $this->newLine();
        $this->line('  <fg=gray>Example:</> <fg=white>php artisan aliaser:help models</>');
        $this->newLine();

        return self::SUCCESS;
    }

    protected function showTopicHelp(string $topic): int
    {
        $method = 'show'.ucfirst($topic).'Help';

        if (! method_exists($this, $method)) {
            $this->components->error("Unknown topic: {$topic}");
            $this->line('  Available topics: <comment>models, forms, objects, collections, enums, livewire</comment>');

            return self::FAILURE;
        }

        return $this->$method();
    }
}
