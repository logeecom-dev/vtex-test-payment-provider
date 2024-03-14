<?php

namespace App\Models;

class BuyerInfo
{
    public string $id;
    public string $firstName;
    public string $lastName;
    public string $document;
    public string $documentType;
    public string $email;
    public string $phone;
    public ?string $tradeName;
    public bool $isCorporate;
    public ?string $corporateDocument;
    public ?string $createdDate;

    public static function fromArray(array $array): static
    {
        $buyerInfo = new static();

        $buyerInfo->id = $array['id'];
        $buyerInfo->firstName = $array['firstName'];
        $buyerInfo->lastName = $array['lastName'];
        $buyerInfo->document = $array['document'];
        $buyerInfo->documentType = $array['documentType'];
        $buyerInfo->email = $array['email'];
        $buyerInfo->phone = $array['phone'];
        $buyerInfo->tradeName = $array['tradeName'];
        $buyerInfo->isCorporate = $array['isCorporate'];
        $buyerInfo->corporateDocument = $array['corporateDocument'];
        $buyerInfo->createdDate = $array['createdDate'];

        return $buyerInfo;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'document' => $this->document,
            'documentType' => $this->documentType,
            'email' => $this->email,
            'phone' => $this->phone,
            'tradeName' => $this->tradeName,
            'isCorporate' => $this->isCorporate,
            'corporateDocument' => $this->corporateDocument,
            'createdDate' => $this->createdDate,
        ];
    }
}
