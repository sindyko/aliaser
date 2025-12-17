<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Fixtures\Objects;

class Money
{
    public function __construct(
        public float $amount,
        public string $currency = 'RUB'
    ) {}

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }

    public function fill(array $data): self
    {
        $this->amount = $data['amount'] ?? 0;
        $this->currency = $data['currency'] ?? 'RUB';

        return $this;
    }
}
