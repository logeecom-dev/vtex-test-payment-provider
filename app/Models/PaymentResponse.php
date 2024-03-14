<?php

namespace App\Models;

class PaymentResponse
{
    public string $paymentId;
    public string $status;
    public ?string $authorizationId;
    public string $paymentUrl;

    public string $tid;
    public ?string $nsu;
    public ?string $acquirer;

    public int $delayToAutoSettle = 604800;

    public int $delayToAutoSettleAfterAntifraud = 604800;

    public int $delayToCancel = 604800;

    public function toArray(): array
    {
        return [
            'paymentId' => $this->paymentId,
            'status' => $this->status,
            'authorizationId' => $this->authorizationId,
            'paymentUrl' => $this->paymentUrl,
            'tid' => $this->tid,
            'nsu' => $this->nsu,
            'acquirer' => $this->acquirer,
            'delayToAutoSettle' => $this->delayToAutoSettle,
            'delayToAutoSettleAfterAntifraud' => $this->delayToAutoSettleAfterAntifraud,
            'delayToCancel' => $this->delayToCancel,
        ];
    }

}
