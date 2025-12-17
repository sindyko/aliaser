<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Unit\Registers;

use Sindyko\Aliaser\Registers\ObjectRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Objects\Money;
use Sindyko\Aliaser\Tests\TestCase;

class ObjectRegistryTest extends TestCase
{
    /** @test */
    public function it_can_register_object_alias(): void
    {
        ObjectRegistry::register('money', Money::class);

        $this->assertEquals(Money::class, ObjectRegistry::find('money'));
    }

    /** @test */
    public function it_can_register_multiple_object_aliases(): void
    {
        objectsMap([
            'money' => Money::class,
            'testDto' => 'App\\DTO\\TestDTO',
        ]);

        $this->assertEquals(Money::class, ObjectRegistry::find('money'));
        $this->assertEquals('App\\DTO\\TestDTO', ObjectRegistry::find('testDto'));
    }

    /** @test */
    public function it_can_find_alias_by_class(): void
    {
        ObjectRegistry::register('money', Money::class);

        $this->assertEquals('money', ObjectRegistry::aliasForClass(Money::class));
    }
}
