<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Unit\Registers;

use Sindyko\Aliaser\Exceptions\AliasNotFoundException;
use Sindyko\Aliaser\Exceptions\DuplicateAliasException;
use Sindyko\Aliaser\Registers\CollectionRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Collections\UserCollection;
use Sindyko\Aliaser\Tests\TestCase;

class CollectionRegistryTest extends TestCase
{
    /** @test */
    public function it_can_register_collection_alias(): void
    {
        CollectionRegistry::register('userCollection', UserCollection::class);

        $this->assertEquals(UserCollection::class, CollectionRegistry::find('userCollection'));
    }

    /** @test */
    public function it_can_register_multiple_collection_aliases(): void
    {
        collectionsMap([
            'userCollection' => UserCollection::class,
            'postCollection' => 'App\\Collections\\PostCollection',
        ]);

        $this->assertEquals(UserCollection::class, CollectionRegistry::find('userCollection'));
        $this->assertEquals('App\\Collections\\PostCollection', CollectionRegistry::find('postCollection'));
    }

    /** @test */
    public function it_returns_null_for_non_existent_alias(): void
    {
        $this->assertNull(CollectionRegistry::find('nonexistent'));
    }

    /** @test */
    public function it_throws_exception_for_non_existent_alias_with_class_for(): void
    {
        $this->expectException(AliasNotFoundException::class);

        CollectionRegistry::classFor('nonexistent');
    }

    /** @test */
    public function it_can_find_alias_by_class(): void
    {
        CollectionRegistry::register('userCollection', UserCollection::class);

        $this->assertEquals('userCollection', CollectionRegistry::aliasForClass(UserCollection::class));
    }

    /** @test */
    public function it_throws_exception_on_duplicate_alias(): void
    {
        CollectionRegistry::register('userCollection', UserCollection::class);

        $this->expectException(DuplicateAliasException::class);

        CollectionRegistry::register('userCollection', 'App\\Collections\\PostCollection');
    }

    /** @test */
    public function it_can_overwrite_when_allowed(): void
    {
        CollectionRegistry::register('userCollection', UserCollection::class);
        CollectionRegistry::register('userCollection', 'App\\Collections\\PostCollection', overwrite: true);

        $this->assertEquals('App\\Collections\\PostCollection', CollectionRegistry::find('userCollection'));
    }

    /** @test */
    public function it_can_check_if_alias_exists(): void
    {
        CollectionRegistry::register('userCollection', UserCollection::class);

        $this->assertTrue(CollectionRegistry::has('userCollection'));
        $this->assertFalse(CollectionRegistry::has('postCollection'));
    }

    /** @test */
    public function it_can_forget_alias(): void
    {
        CollectionRegistry::register('userCollection', UserCollection::class);
        CollectionRegistry::forget('userCollection');

        $this->assertFalse(CollectionRegistry::has('userCollection'));
    }

    /** @test */
    public function it_can_get_all_aliases(): void
    {
        collectionsMap([
            'userCollection' => UserCollection::class,
            'postCollection' => 'App\\Collections\\PostCollection',
        ]);

        $map = CollectionRegistry::getMap();

        $this->assertCount(2, $map);
        $this->assertEquals(UserCollection::class, $map['userCollection']);
    }

    /** @test */
    public function it_can_clear_all_aliases(): void
    {
        collectionsMap([
            'userCollection' => UserCollection::class,
        ]);

        CollectionRegistry::clear();

        $this->assertEmpty(CollectionRegistry::getMap());
    }
}
