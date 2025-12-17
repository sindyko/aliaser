<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Unit\Registers;

use Sindyko\Aliaser\Exceptions\AliasNotFoundException;
use Sindyko\Aliaser\Exceptions\DuplicateAliasException;
use Sindyko\Aliaser\Registers\FormRegistry;
use Sindyko\Aliaser\Tests\Fixtures\Forms\TestForm;
use Sindyko\Aliaser\Tests\TestCase;

class FormRegistryTest extends TestCase
{
    /** @test */
    public function it_can_register_form_alias(): void
    {
        FormRegistry::register('testForm', TestForm::class);

        $this->assertEquals(TestForm::class, FormRegistry::find('testForm'));
    }

    /** @test */
    public function it_can_register_multiple_form_aliases(): void
    {
        formsMap([
            'testForm' => TestForm::class,
            'userForm' => 'App\\Forms\\UserForm',
        ]);

        $this->assertEquals(TestForm::class, FormRegistry::find('testForm'));
        $this->assertEquals('App\\Forms\\UserForm', FormRegistry::find('userForm'));
    }

    /** @test */
    public function it_returns_null_for_non_existent_alias(): void
    {
        $this->assertNull(FormRegistry::find('nonexistent'));
    }

    /** @test */
    public function it_throws_exception_for_non_existent_alias_with_class_for(): void
    {
        $this->expectException(AliasNotFoundException::class);

        FormRegistry::classFor('nonexistent');
    }

    /** @test */
    public function it_can_find_alias_by_class(): void
    {
        FormRegistry::register('testForm', TestForm::class);

        $this->assertEquals('testForm', FormRegistry::aliasForClass(TestForm::class));
    }

    /** @test */
    public function it_throws_exception_on_duplicate_alias(): void
    {
        FormRegistry::register('testForm', TestForm::class);

        $this->expectException(DuplicateAliasException::class);

        FormRegistry::register('testForm', 'App\\Forms\\UserForm');
    }

    /** @test */
    public function it_can_overwrite_when_allowed(): void
    {
        FormRegistry::register('testForm', TestForm::class);
        FormRegistry::register('testForm', 'App\\Forms\\UserForm', overwrite: true);

        $this->assertEquals('App\\Forms\\UserForm', FormRegistry::find('testForm'));
    }

    /** @test */
    public function it_can_check_if_alias_exists(): void
    {
        FormRegistry::register('testForm', TestForm::class);

        $this->assertTrue(FormRegistry::has('testForm'));
        $this->assertFalse(FormRegistry::has('userForm'));
    }

    /** @test */
    public function it_can_forget_alias(): void
    {
        FormRegistry::register('testForm', TestForm::class);
        FormRegistry::forget('testForm');

        $this->assertFalse(FormRegistry::has('testForm'));
    }

    /** @test */
    public function it_can_get_all_aliases(): void
    {
        formsMap([
            'testForm' => TestForm::class,
            'userForm' => 'App\\Forms\\UserForm',
        ]);

        $map = FormRegistry::getMap();

        $this->assertCount(2, $map);
        $this->assertEquals(TestForm::class, $map['testForm']);
    }

    /** @test */
    public function it_can_clear_all_aliases(): void
    {
        formsMap([
            'testForm' => TestForm::class,
        ]);

        FormRegistry::clear();

        $this->assertEmpty(FormRegistry::getMap());
    }
}
