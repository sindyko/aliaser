<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Feature\Livewire;

use Sindyko\Aliaser\Livewire\ModelAliasSynth;
use Sindyko\Aliaser\Registers\ModelRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Models\User;
use Sindyko\Aliaser\Tests\TestCase;

class ModelAliasSynthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(\Livewire\Livewire::class)) {
            $this->markTestSkipped('Livewire is not installed');
        }

        ModelRegistry::register('user', User::class);
    }

    /** @test */
    public function it_matches_eloquent_models(): void
    {
        $user = User::find(1);

        $this->assertTrue(ModelAliasSynth::match($user));
    }

    /** @test */
    public function it_does_not_match_non_models(): void
    {
        $this->assertFalse(ModelAliasSynth::match('string'));
        $this->assertFalse(ModelAliasSynth::match(123));
        $this->assertFalse(ModelAliasSynth::match([]));
        $this->assertFalse(ModelAliasSynth::match(new \stdClass()));
    }

    /** @test */
    public function it_can_find_registered_alias(): void
    {
        $alias = ModelRegistry::aliasForClass(User::class);

        $this->assertEquals('user', $alias);
    }

    /** @test */
    public function it_can_find_class_from_alias(): void
    {
        $class = ModelRegistry::find('user');

        $this->assertEquals(User::class, $class);
    }
}
