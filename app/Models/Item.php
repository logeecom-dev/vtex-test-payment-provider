<?php

namespace App\Models;

class Item
{
    public string $id;
    public string $name;
    public float $price;
    public int $quantity;
    public float $discount;

    public static function fromArray(array $array): static
    {
        $item = new static();

        $item->id = $array['id'];
        $item->name = $array['name'];
        $item->price = $array['price'];
        $item->quantity = $array['quantity'];
        $item->discount = $array['discount'];

        return $item;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
        ];
    }
}
