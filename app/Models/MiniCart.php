<?php

namespace App\Models;

class MiniCart
{
    public float $shippingValue;

    public float $taxValue;

    public BuyerInfo $buyer;

    public Address $shippingAddress;

    public Address $billingAddress;
    /**
     * @var Item[]
     */
    public array $items = [];

    public static function fromArray(array $array): static
    {
        $miniCart = new static();
        $items = [];

        $miniCart->shippingValue = $array['shippingValue'];
        $miniCart->taxValue = $array['taxValue'];
        $miniCart->buyer = BuyerInfo::fromArray($array['buyer']);
        $miniCart->shippingAddress = Address::fromArray($array['shippingAddress']);
        $miniCart->billingAddress = Address::fromArray($array['billingAddress']);

        foreach ($array['items'] as $item) {
            $items[] = Item::fromArray($item);
        }

        $miniCart->items = $items;

        return $miniCart;
    }

    public function toArray(): array
    {
        $array = [
            'shippingValue' => $this->shippingValue,
            'taxValue' => $this->taxValue,
            'buyer' => $this->buyer->toArray(),
            'shippingAddress' => $this->shippingAddress->toArray(),
            'billingAddress' => $this->billingAddress->toArray(),
        ];

        $cartItems = [];

        foreach ($this->items as $item) {
            $cartItems[] = $item->toArray();
        }

        $array['items'] = $cartItems;

        return $array;
    }
}
