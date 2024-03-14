<?php

namespace App\Models;

class CustomField
{
    public string $name;

    public string $type;

    public array $options = [];

    public function __construct($name, $type, $options = [])
    {
        $this->name = $name;
        $this->type = $type;

        if (!empty($options)) {
            $this->options = $options;
        }
    }

    public function toArray(): array
    {
        $array = [
            'name' => $this->name,
            'type' => $this->type,
        ];

        if (!empty($this->options)) {
            $array['options'] = $this->options;
        }

        return $array;
    }
}
