<?php

namespace App\Models;

class Address
{
    public string $country;
    public string $street;
    public string $number;
    public string $complement;
    public string $neighborhood;
    public string $postalCode;
    public string $city;
    public string $state;

    public static function fromArray(array $array): static
    {
        $address = new static();

        $address->country = $array['country'];
        $address->street = $array['street'];
        $address->number = $array['number'];
        $address->complement = $array['complement'];
        $address->neighborhood = $array['neighborhood'];
        $address->postalCode = $array['postalCode'];
        $address->city = $array['city'];
        $address->state = $array['state'];

        return $address;
    }

    public function toArray(): array
    {
        return [
            'country' => $this->country,
            'street' => $this->street,
            'number' => $this->number,
            'complement' => $this->complement,
            'neighborhood' => $this->neighborhood,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}
