<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Fixtures\Objects;

class TestDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public bool $active = true
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'active' => $this->active,
        ];
    }

    public function fill(array $data): self
    {
        $this->title = $data['title'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->active = $data['active'] ?? true;

        return $this;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'] ?? '',
            $data['description'] ?? '',
            $data['active'] ?? true
        );
    }
}
