<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Feature\Livewire;

use Illuminate\Support\Collection;
use Sindyko\Aliaser\Livewire\CollectionAliasSynth;
use Sindyko\Aliaser\Registers\CollectionRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Collections\UserCollection;
use Sindyko\Aliaser\Tests\TestCase;

class CollectionAliasSynthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(\Livewire\Livewire::class)) {
            $this->markTestSkipped('Livewire is not installed');
        }

        CollectionRegistry::register('userCollection', UserCollection::class);
    }

    /** @test */
    public function it_matches_registered_collections(): void
    {
        $collection = new UserCollection([
            ['name' => 'John', 'age' => 30],
        ]);

        $this->assertTrue(CollectionAliasSynth::match($collection));
    }

    /** @test */
    public function it_does_not_match_base_collection(): void
    {
        $collection = collect(['item_1', 'item_2']);

        $this->assertTrue(CollectionAliasSynth::match($collection));

        $this->assertNull(CollectionRegistry::aliasForClass(Collection::class));
    }

    /** @test */
    public function it_can_find_registered_alias(): void
    {
        $class = CollectionRegistry::find('userCollection');

        $this->assertEquals(UserCollection::class, $class);
    }

    /** @test */
    public function it_has_correct_synth_key(): void
    {
        $this->assertEquals('clctn-alias', CollectionAliasSynth::$key);
    }
}
