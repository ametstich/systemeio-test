<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseRequest extends BaseRequest
{
    #[NotBlank]
    public ?string $product;

    #[NotBlank]
    public ?string $taxNumber;

    #[Length(min: 2, max: 4)]
    public ?string $couponCode = null;

    #[NotBlank]
    public ?string $paymentProcessor;
}