<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Feature\Livewire;

use Sindyko\Aliaser\Livewire\EnumAliasSynth;
use Sindyko\Aliaser\Registers\EnumRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Enums\UserStatus;
use Sindyko\Aliaser\Tests\TestCase;

class EnumAliasSynthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(\Livewire\Livewire::class)) {
            $this->markTestSkipped('Livewire is not installed');
        }

        EnumRegistry::register('userStatus', UserStatus::class);
    }

    /** @test */
    public function it_matches_registered_enums(): void
    {
        $status = UserStatus::ACTIVE;

        $this->assertTrue(EnumAliasSynth::match($status));
    }

    /** @test */
    public function it_matches_all_enum_cases(): void
    {
        $this->assertTrue(EnumAliasSynth::match(UserStatus::ACTIVE));
        $this->assertTrue(EnumAliasSynth::match(UserStatus::INACTIVE));
        $this->assertTrue(EnumAliasSynth::match(UserStatus::BANNED));
    }

    /** @test */
    public function it_can_find_registered_alias(): void
    {
        $class = EnumRegistry::find('userStatus');

        $this->assertEquals(UserStatus::class, $class);
    }

    /** @test */
    public function it_can_find_alias_by_class(): void
    {
        $alias = EnumRegistry::aliasForClass(UserStatus::class);

        $this->assertEquals('userStatus', $alias);
    }

    /** @test */
    public function it_has_correct_synth_key(): void
    {
        $this->assertEquals('enm-alias', EnumAliasSynth::$key);
    }
}
