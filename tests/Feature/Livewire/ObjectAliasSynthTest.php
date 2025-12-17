<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Feature\Livewire;

use Sindyko\Aliaser\Livewire\ObjectAliasSynth;
use Sindyko\Aliaser\Registers\ObjectRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Objects\Money;
use Sindyko\Aliaser\Tests\Fixtures\Objects\TestDTO;
use Sindyko\Aliaser\Tests\TestCase;

class ObjectAliasSynthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(\Livewire\Livewire::class)) {
            $this->markTestSkipped('Livewire is not installed');
        }

        ObjectRegistry::register('testDto', TestDTO::class);
        ObjectRegistry::register('money', Money::class);
    }

    /** @test */
    public function it_matches_registered_objects(): void
    {
        $dto = new TestDTO('Test', 'Description');

        $this->assertTrue(ObjectAliasSynth::match($dto));
    }

    /** @test */
    public function it_does_not_match_standard_types(): void
    {
        $this->assertFalse(ObjectAliasSynth::match(new \stdClass()));
        $this->assertFalse(ObjectAliasSynth::match(new \DateTime()));
    }

    /** @test */
    public function it_does_not_match_unregistered_objects(): void
    {
        $unregistered = new class()
        {
            public string $prop = 'value';
        };

        $this->assertFalse(ObjectAliasSynth::match($unregistered));
    }

    /** @test */
    public function it_can_find_registered_aliases(): void
    {
        $this->assertEquals(TestDTO::class, ObjectRegistry::find('testDto'));
        $this->assertEquals(Money::class, ObjectRegistry::find('money'));
    }

    /** @test */
    public function it_has_correct_synth_key(): void
    {
        $this->assertEquals('obj-alias', ObjectAliasSynth::$key);
    }
}
