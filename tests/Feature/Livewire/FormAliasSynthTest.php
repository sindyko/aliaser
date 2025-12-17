<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Feature\Livewire;

use Sindyko\Aliaser\Livewire\FormAliasSynth;
use Sindyko\Aliaser\Registers\FormRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Forms\TestForm;
use Sindyko\Aliaser\Tests\TestCase;

class FormAliasSynthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! class_exists(\Livewire\Livewire::class)) {
            $this->markTestSkipped('Livewire is not installed');
        }

        FormRegistry::register('testForm', TestForm::class);
    }

    /** @test */
    public function it_can_register_form_alias(): void
    {
        $class = FormRegistry::find('testForm');

        $this->assertEquals(TestForm::class, $class);
    }

    /** @test */
    public function it_can_find_alias_by_class(): void
    {
        $alias = FormRegistry::aliasForClass(TestForm::class);

        $this->assertEquals('testForm', $alias);
    }

    /** @test */
    public function it_has_correct_synth_key(): void
    {
        $this->assertEquals('frm-alias', FormAliasSynth::$key);
    }
}
