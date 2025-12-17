<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Fixtures\Collections;

use Illuminate\Support\Collection;

class UserCollection extends Collection
{
    public function active(): self
    {
        return $this->filter(fn ($user) => $user['status'] === 'active');
    }

    public function names(): array
    {
        return $this->pluck('name')->toArray();
    }

    public function totalAge(): int
    {
        return $this->sum('age');
    }
}
