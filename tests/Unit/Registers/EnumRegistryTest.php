<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Unit\Registers;

use Sindyko\Aliaser\Exceptions\AliasNotFoundException;
use Sindyko\Aliaser\Exceptions\DuplicateAliasException;
use Sindyko\Aliaser\Registers\EnumRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Enums\UserStatus;
use Sindyko\Aliaser\Tests\TestCase;

class EnumRegistryTest extends TestCase
{
    /** @test */
    public function it_can_register_enum_alias(): void
    {
        EnumRegistry::register('userStatus', UserStatus::class);

        $this->assertEquals(UserStatus::class, EnumRegistry::find('userStatus'));
    }

    /** @test */
    public function it_can_register_multiple_enum_aliases(): void
    {
        enumsMap([
            'userStatus' => UserStatus::class,
            'postStatus' => 'App\\Enums\\PostStatus',
        ]);

        $this->assertEquals(UserStatus::class, EnumRegistry::find('userStatus'));
        $this->assertEquals('App\\Enums\\PostStatus', EnumRegistry::find('postStatus'));
    }

    /** @test */
    public function it_returns_null_for_non_existent_alias(): void
    {
        $this->assertNull(EnumRegistry::find('nonexistent'));
    }

    /** @test */
    public function it_throws_exception_for_non_existent_alias_with_class_for(): void
    {
        $this->expectException(AliasNotFoundException::class);

        EnumRegistry::classFor('nonexistent');
    }

    /** @test */
    public function it_can_find_alias_by_class(): void
    {
        EnumRegistry::register('userStatus', UserStatus::class);

        $this->assertEquals('userStatus', EnumRegistry::aliasForClass(UserStatus::class));
    }

    /** @test */
    public function it_throws_exception_on_duplicate_alias(): void
    {
        EnumRegistry::register('userStatus', UserStatus::class);

        $this->expectException(DuplicateAliasException::class);

        EnumRegistry::register('userStatus', 'App\\Enums\\PostStatus');
    }

    /** @test */
    public function it_can_overwrite_when_allowed(): void
    {
        EnumRegistry::register('userStatus', UserStatus::class);
        EnumRegistry::register('userStatus', 'App\\Enums\\PostStatus', overwrite: true);

        $this->assertEquals('App\\Enums\\PostStatus', EnumRegistry::find('userStatus'));
    }

    /** @test */
    public function it_can_check_if_alias_exists(): void
    {
        EnumRegistry::register('userStatus', UserStatus::class);

        $this->assertTrue(EnumRegistry::has('userStatus'));
        $this->assertFalse(EnumRegistry::has('postStatus'));
    }

    /** @test */
    public function it_can_forget_alias(): void
    {
        EnumRegistry::register('userStatus', UserStatus::class);
        EnumRegistry::forget('userStatus');

        $this->assertFalse(EnumRegistry::has('userStatus'));
    }

    /** @test */
    public function it_can_get_all_aliases(): void
    {
        enumsMap([
            'userStatus' => UserStatus::class,
            'postStatus' => 'App\\Enums\\PostStatus',
        ]);

        $map = EnumRegistry::getMap();

        $this->assertCount(2, $map);
        $this->assertEquals(UserStatus::class, $map['userStatus']);
    }

    /** @test */
    public function it_can_clear_all_aliases(): void
    {
        enumsMap([
            'userStatus' => UserStatus::class,
        ]);

        EnumRegistry::clear();

        $this->assertEmpty(EnumRegistry::getMap());
    }
}
