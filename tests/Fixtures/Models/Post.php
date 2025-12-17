<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class Post extends Model
{
    use Sushi;

    protected array $rows = [
        [
            'id' => 1,
            'title' => 'First Post',
            'user_id' => 1,
        ],
        [
            'id' => 2,
            'title' => 'Second Post',
            'user_id' => 1,
        ],
        [
            'id' => 3,
            'title' => 'Third Post',
            'user_id' => 2,
        ],
    ];

    protected $fillable = [
        'title',
        'user_id',
    ];

    protected function sushiShouldCache(): bool
    {
        return false;
    }
}
