<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Unit\Support;

use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Support\EntityManager;
use Sindyko\Aliaser\Support\ModelProxy;
use Sindyko\Aliaser\Tests\Fixtures\Models\User;
use Sindyko\Aliaser\Tests\TestCase;

class EntityManagerTest extends TestCase
{
    protected EntityManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = new EntityManager();
        ModelRegistry::register('user', User::class);
    }

    /** @test */
    public function it_returns_proxy_when_no_arguments(): void
    {
        $proxy = $this->manager->user();

        $this->assertInstanceOf(ModelProxy::class, $proxy);
        $this->assertEquals(User::class, $proxy->class());
    }

    /** @test */
    public function it_finds_model_by_single_id(): void
    {
        $user = $this->manager->user(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->id);
        $this->assertEquals('John Doe', $user->name);
    }

    /** @test */
    public function it_finds_multiple_models_by_array_of_ids(): void
    {
        $users = $this->manager->user([1, 2]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $users);
        $this->assertCount(2, $users);
        $this->assertEquals(1, $users[0]->id);
        $this->assertEquals(2, $users[1]->id);
    }

    /** @test */
    public function it_returns_null_when_model_not_found(): void
    {
        $user = $this->manager->user(999);

        $this->assertNull($user);
    }

    /** @test */
    public function it_throws_exception_for_unknown_alias(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Model alias [unknown] not found');

        $this->manager->unknown(1);
    }

    /** @test */
    public function it_can_chain_query_methods(): void
    {
        $proxy = $this->manager->user();
        $users = $proxy->where('id', '>', 0)->get();

        $this->assertGreaterThanOrEqual(2, $users->count());
    }
}
