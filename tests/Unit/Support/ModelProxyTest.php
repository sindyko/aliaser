<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Unit\Support;

use Sindyko\Aliaser\Support\ModelProxy;
use Sindyko\Aliaser\Tests\Fixtures\Models\User;
use Sindyko\Aliaser\Tests\TestCase;

class ModelProxyTest extends TestCase
{
    /** @test */
    public function it_can_get_model_class(): void
    {
        $proxy = new ModelProxy(User::class);

        $this->assertEquals(User::class, $proxy->class());
    }

    /** @test */
    public function it_can_get_query_builder(): void
    {
        $proxy = new ModelProxy(User::class);
        $query = $proxy->query();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Builder::class, $query);
    }

    /** @test */
    public function it_can_make_new_model_instance(): void
    {
        $proxy = new ModelProxy(User::class);
        $user = $proxy->make(['name' => 'Test User']);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertFalse($user->exists);
    }

    /** @test */
    public function it_automatically_forwards_find_method(): void
    {
        $proxy = new ModelProxy(User::class);
        $user = $proxy->find(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->id);
    }

    /** @test */
    public function it_automatically_forwards_find_many_method(): void
    {
        $proxy = new ModelProxy(User::class);
        $users = $proxy->findMany([1, 2]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $users);
        $this->assertCount(2, $users);
    }

    /** @test */
    public function it_automatically_forwards_all_method(): void
    {
        $proxy = new ModelProxy(User::class);
        $users = $proxy->all();

        $this->assertGreaterThanOrEqual(2, $users->count());
    }

    /** @test */
    public function it_automatically_forwards_where_method(): void
    {
        $proxy = new ModelProxy(User::class);
        $user = $proxy->where('id', 1)->first();

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
    }

    /** @test */
    public function it_automatically_forwards_create_method(): void
    {
        $proxy = new ModelProxy(User::class);
        $user = $proxy->create([
            'name' => 'New User',
            'email' => 'new@example.com',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($user->exists);
        $this->assertEquals('New User', $user->name);
    }

    /** @test */
    public function it_can_chain_multiple_methods(): void
    {
        $proxy = new ModelProxy(User::class);
        $users = $proxy
            ->where('id', '>', 0)
            ->orderBy('id', 'desc')
            ->limit(2)
            ->get();

        $this->assertCount(2, $users);
    }
}
