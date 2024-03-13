<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CalculatePriceRequest extends BaseRequest
{
    #[NotBlank]
    public ?string $product;

    #[NotBlank]
    #[Length(min: 4, max: 15)]
    public ?string $taxNumber;

    #[Length(min: 2, max: 4)]
    public ?string $couponCode = null;
}