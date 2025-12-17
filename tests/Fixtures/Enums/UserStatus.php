<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Fixtures\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';
}
