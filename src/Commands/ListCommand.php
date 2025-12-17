<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Commands;

use Illuminate\Console\Command;
use Sindyko\Aliaser\Registers\CollectionRegistry;
use Sindyko\Aliaser\Registers\EnumRegistry;
use Sindyko\Aliaser\Registers\FormRegistry;
use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Registers\ObjectRegistry;

class ListCommand extends Command
{
    protected $signature = 'aliaser:list
                            {--models : Show only model aliases}
                            {--forms : Show only form aliases}
                            {--objects : Show only object aliases}
                            {--collections : Show only collection aliases}
                            {--enums : Show only enum aliases}
                            {--json : Output as JSON}';

    protected $description = 'List all registered aliases';

    protected array $registries = [];

    protected array $typeConfig = [
        'models' => ['title' => 'Models', 'color' => 'yellow'],
        'forms' => ['title' => 'Forms', 'color' => 'blue'],
        'objects' => ['title' => 'Objects', 'color' => 'magenta'],
        'collections' => ['title' => 'Collections', 'color' => 'cyan'],
        'enums' => ['title' => 'Enums', 'color' => 'green'],
    ];

    public function handle(): int
    {
        $this->registries = [
            'models' => ModelRegistry::getMap(),
            'forms' => FormRegistry::getMap(),
            'objects' => ObjectRegistry::getMap(),
            'collections' => CollectionRegistry::getMap(),
            'enums' => EnumRegistry::getMap(),
        ];

        // Фильтрация по опциям
        $filter = $this->getFilterOption();

        if ($filter) {
            $this->registries = [$filter => $this->registries[$filter]];
        }

        // JSON вывод
        if ($this->option('json')) {
            $this->outputJson();

            return self::SUCCESS;
        }

        // Обычный вывод
        $this->outputTable($filter);

        return self::SUCCESS;
    }

    protected function getFilterOption(): ?string
    {
        foreach (array_keys($this->typeConfig) as $type) {
            if ($this->option($type)) {
                return $type;
            }
        }

        return null;
    }

    protected function outputTable(?string $filter): void
    {
        $totalCount = array_sum(array_map('count', $this->registries));

        if ($totalCount === 0) {
            $this->showEmptyState();

            return;
        }

        $this->newLine();

        if ($filter) {
            $this->outputSingleTypeTable($filter);
        } else {
            $this->outputAllTypesTable();
        }

        $this->showSummary($filter);
    }

    protected function outputSingleTypeTable(string $type): void
    {
        $config = $this->typeConfig[$type];
        $items = $this->registries[$type] ?? [];

        if (empty($items)) {
            $this->components->warn("No {$config['title']} aliases registered.");

            return;
        }

        $this->components->info("{$config['title']} (".count($items).')');
        $this->newLine();

        $rows = [];
        foreach ($items as $alias => $class) {
            $rows[] = [
                'alias' => "<fg=green>{$alias}</>",
                'class' => "<fg=cyan>{$class}</>",
            ];
        }

        $this->table(['Alias', 'Class'], $rows);
    }

    protected function outputAllTypesTable(): void
    {
        $this->components->info('Registered Aliases');
        $this->newLine();

        $rows = [];

        foreach ($this->typeConfig as $type => $config) {
            $items = $this->registries[$type] ?? [];

            foreach ($items as $alias => $class) {
                $rows[] = [
                    'type' => "<fg={$config['color']}>{$config['title']}</>",
                    'alias' => "<fg=green>{$alias}</>",
                    'class' => "<fg=cyan>{$class}</>",
                ];
            }
        }

        if (empty($rows)) {
            return;
        }

        $this->table(['Type', 'Alias', 'Class'], $rows);
    }

    protected function showSummary(?string $filter): void
    {
        $this->newLine();

        if ($filter) {
            $count = count($this->registries[$filter] ?? []);
            $this->line("  Total: <comment>{$count}</comment> {$this->typeConfig[$filter]['title']} registered");
        } else {
            $totalCount = array_sum(array_map('count', $this->registries));
            $this->line("  Total: <comment>{$totalCount}</comment> aliases registered");

            $breakdown = [];
            foreach ($this->typeConfig as $type => $config) {
                $count = count($this->registries[$type] ?? []);
                if ($count > 0) {
                    $breakdown[] = "<comment>{$count}</comment> {$config['title']}";
                }
            }

            if (count($breakdown) > 1) {
                $this->line('  ('.implode(', ', $breakdown).')');
            }
        }

        $this->newLine();
    }

    protected function showEmptyState(): void
    {
        $this->newLine();
        $this->components->warn('No aliases registered yet.');
        $this->newLine();
        $this->line('  <fg=gray>Register aliases in your</> <comment>AppServiceProvider</comment><fg=gray>:</fg=gray>');
        $this->newLine();
        $this->line('  <fg=white>modelsMap([\'user\' => User::class]);</>');
        $this->line('  <fg=white>formsMap([\'postForm\' => PostForm::class]);</>');
        $this->line('  <fg=white>objectsMap([\'money\' => Money::class]);</>');
        $this->newLine();
    }

    protected function outputJson(): void
    {
        $data = $this->registries;
        $data['total'] = array_sum(array_map('count', $this->registries));

        $this->line(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
