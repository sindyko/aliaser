<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Feature;

use Sindyko\Aliaser\Facades\Entity;
use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Models\Post;
use Sindyko\Aliaser\Tests\Fixtures\Models\User;
use Sindyko\Aliaser\Tests\TestCase;

class EntityFacadeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ModelRegistry::register('user', User::class);
        ModelRegistry::register('post', Post::class);
    }

    /** @test */
    public function it_resolves_entity_manager_from_container(): void
    {
        $manager = app('entity.manager');

        $this->assertInstanceOf(\Sindyko\Aliaser\Support\EntityManager::class, $manager);
    }

    /** @test */
    public function it_can_access_models_via_facade(): void
    {
        $user = Entity::user(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->id);
    }

    /** @test */
    public function it_can_find_multiple_models_via_facade(): void
    {
        $users = Entity::user([1, 2]);

        $this->assertCount(2, $users);
    }

    /** @test */
    public function it_returns_proxy_for_query_building(): void
    {
        $proxy = Entity::user();

        $this->assertInstanceOf(\Sindyko\Aliaser\Support\ModelProxy::class, $proxy);
    }

    /** @test */
    public function it_can_chain_query_methods_via_facade(): void
    {
        $users = Entity::user()->where('id', '>', 0)->get();

        $this->assertGreaterThanOrEqual(2, $users->count());
    }

    /** @test */
    public function it_throws_exception_for_unknown_alias(): void
    {
        $this->expectException(\BadMethodCallException::class);

        Entity::unknown(1);
    }
}
