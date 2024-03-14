<?php

namespace App\Models;

class RedirectRequest
{
    public string $reference;

    public string $orderId;

    public string $shopperInteraction;

    public string $transactionId;

    public string $paymentId;

    public string $paymentMethod;

    public ?string $paymentMethodCustomCode;

    public string $merchantName;

    public float $value;

    public string $currency;

    public string $installments;

    public string $deviceFingerprint;

    public MiniCart $miniCart;

    public string $url;

    public string $returnUrl;

    public string $callbackUrl;

    public static function fromArray(array $array): static
    {
        $request = new static();

        $request->reference = $array['reference'];
        $request->orderId = $array['orderId'];
        $request->shopperInteraction = $array['shopperInteraction'];
        $request->transactionId = $array['transactionId'];
        $request->paymentId = $array['paymentId'];
        $request->paymentMethod = $array['paymentMethod'];
        $request->paymentMethodCustomCode = $array['paymentMethodCustomCode'];
        $request->merchantName = $array['merchantName'];
        $request->value = $array['value'];
        $request->currency = $array['currency'];
        $request->installments = $array['installments'];
        $request->deviceFingerprint = $array['deviceFingerprint'];
        $request->miniCart = MiniCart::fromArray($array['miniCart']);
        $request->url = $array['url'];
        $request->returnUrl = $array['returnUrl'];
        $request->callbackUrl = $array['callbackUrl'];

        return $request;
    }

    public function toArray(): array
    {
        return [
            'reference' => $this->reference,
            'orderId' => $this->orderId,
            'shopperInteraction' => $this->shopperInteraction,
            'transactionId' => $this->transactionId,
            'paymentId' => $this->paymentId,
            'paymentMethod' => $this->paymentMethod,
            'paymentMethodCustomCode' => $this->paymentMethodCustomCode,
            'merchantName' => $this->merchantName,
            'value' => $this->value,
            'currency' => $this->currency,
            'installments' => $this->installments,
            'deviceFingerprint' => $this->deviceFingerprint,
            'miniCart' => $this->miniCart->toArray(),
            'url' => $this->url,
            'returnUrl' => $this->returnUrl,
            'callbackUrl' => $this->callbackUrl,
        ];
    }
}
