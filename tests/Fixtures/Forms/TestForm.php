<?php

declare(strict_types=1);

namespace Sindyko\Aliaser\Tests\Fixtures\Forms;

use Livewire\Form;

class TestForm extends Form
{
    public string $name = '';

    public string $email = '';

    public ?int $age = null;

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email',
            'age' => 'nullable|integer|min:18',
        ];
    }
}
