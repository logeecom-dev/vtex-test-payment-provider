<?php

namespace App\Models;

class Manifest
{
    /**
     * @var PaymentMethodInfo[]
     */
    public array $paymentMethods;

    /**
     * @var CustomField[]
     */
    public array $customFields;

    public function toArray(): array
    {
        $paymentMethodArray = [];
        $customFieldArray = [];

        foreach ($this->paymentMethods as $paymentMethod) {
            $paymentMethodArray[] = $paymentMethod->toArray();
        }

        foreach ($this->customFields as $customField) {
            $customFieldArray[] = $customField->toArray();
        }

        return [
            'paymentMethods' => $paymentMethodArray,
            'customFields' => $customFieldArray,
        ];
    }
}
