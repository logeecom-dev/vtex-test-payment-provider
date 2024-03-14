<?php

namespace App\Models;

class PaymentMethodInfo
{
    public string $name;

    public string $allowsSplit;

    public function __construct($name, $allowsSplit = 'disabled')
    {
        $this->name = $name;
        $this->allowsSplit = $allowsSplit;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'allowsSplit' => $this->allowsSplit,
        ];
    }
}
