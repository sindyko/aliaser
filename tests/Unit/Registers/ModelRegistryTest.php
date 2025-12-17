<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Unit\Registers;

use Sindyko\Aliaser\Exceptions\AliasNotFoundException;
use Sindyko\Aliaser\Exceptions\DuplicateAliasException;
use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Models\Post;
use Sindyko\Aliaser\Tests\Fixtures\Models\User;
use Sindyko\Aliaser\Tests\TestCase;

class ModelRegistryTest extends TestCase
{
    /** @test */
    public function it_can_register_model_alias(): void
    {
        ModelRegistry::register('user', User::class);

        $this->assertEquals(User::class, ModelRegistry::find('user'));
    }

    /** @test */
    public function it_can_register_multiple_aliases(): void
    {
        modelsMap([
            'user' => User::class,
            'post' => Post::class,
        ]);

        $this->assertEquals(User::class, ModelRegistry::find('user'));
        $this->assertEquals(Post::class, ModelRegistry::find('post'));
    }

    /** @test */
    public function it_returns_null_for_non_existent_alias(): void
    {
        $this->assertNull(ModelRegistry::find('nonexistent'));
    }

    /** @test */
    public function it_throws_exception_for_non_existent_alias_with_class_for(): void
    {
        $this->expectException(AliasNotFoundException::class);

        ModelRegistry::classFor(alias: 'nonexistent');
    }

    /** @test */
    public function it_can_find_alias_by_class(): void
    {
        ModelRegistry::register('user', User::class);

        $this->assertEquals('user', ModelRegistry::aliasForClass(User::class));
    }

    /** @test */
    public function it_returns_null_when_class_not_registered(): void
    {
        $this->assertNull(ModelRegistry::aliasForClass(User::class));
    }

    /** @test */
    public function it_throws_exception_on_duplicate_alias(): void
    {
        ModelRegistry::register('user', User::class);

        $this->expectException(DuplicateAliasException::class);

        ModelRegistry::register('user', Post::class);
    }

    /** @test */
    public function it_can_overwrite_when_allowed(): void
    {
        ModelRegistry::register('user', User::class);
        ModelRegistry::register('user', Post::class, overwrite: true);

        $this->assertEquals(Post::class, ModelRegistry::find('user'));
    }

    /** @test */
    public function it_can_check_if_alias_exists(): void
    {
        ModelRegistry::register('user', User::class);

        $this->assertTrue(ModelRegistry::has('user'));
        $this->assertFalse(ModelRegistry::has('post'));
    }

    /** @test */
    public function it_can_forget_alias(): void
    {
        ModelRegistry::register('user', User::class);
        ModelRegistry::forget('user');

        $this->assertFalse(ModelRegistry::has('user'));
    }

    /** @test */
    public function it_can_get_all_aliases(): void
    {
        modelsMap([
            'user' => User::class,
            'post' => Post::class,
        ]);

        $map = ModelRegistry::getMap();

        $this->assertCount(2, $map);
        $this->assertEquals(User::class, $map['user']);
        $this->assertEquals(Post::class, $map['post']);
    }

    /** @test */
    public function it_can_clear_all_aliases(): void
    {
        modelsMap([
            'user' => User::class,
            'post' => Post::class,
        ]);

        ModelRegistry::clear();

        $this->assertEmpty(ModelRegistry::getMap());
    }
}
