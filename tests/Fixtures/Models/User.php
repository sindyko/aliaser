<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class User extends Model
{
    use Sushi;

    protected array $rows = [
        [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ],
        [
            'id' => 2,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ],
    ];

    protected $fillable = [
        'name',
        'email',
    ];

    protected function sushiShouldCache(): bool
    {
        return false;
    }
}
