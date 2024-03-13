<?php

namespace App\Services;

use App\Data\CountryTaxListData;
use App\Data\DiscountListData;
use App\Data\ProductListData;
use App\Requests\BaseRequest;
use Exception;
use Throwable;

class PriceService
{
    private float $productPriceAmount = 0;
    private string $tax;
    private float $taxAmount = 0;
    private array $discount = ['type' => null, 'value' => 0];
    private float $discountAmount = 0;
    private array $error = [];

    public function __construct(

        private ProductListData    $productListData,
        private CountryTaxListData $countryTaxListData,
        private DiscountListData   $discountListData,
    )
    {
        $this->productListData = new ProductListData();
        $this->countryTaxListData = new CountryTaxListData();
        $this->discountListData = new DiscountListData();
    }

    /**
     * @throws Exception
     */
    public function calculatePrice(BaseRequest $request): array
    {
        if (!$product = $this->productListData->find($request->product)) {
            throw new Exception('Product not found');
        }

        $this->productPriceAmount = $product->price;
        $this->tax = $this->countryTaxListData->find($request->taxNumber);
        try {
            $this->discount = $this->discountListData->find($request->couponCode);
        } catch (Throwable $th) {
            $this->error[] = $th->getMessage();
        }
        $total = $this->calculateTotal();
        $response['data'] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'tax' => $this->taxAmount,
            'discount' => $this->discountAmount,
            'total' => $total
        ];
        if (!empty($errors = $this->error)) {
            $response['errors'] = $errors;
        }

        return $response;
    }

    public function getErrors(): array
    {
        return $this->error;
    }

    private function calculateTotal(): float
    {
        $total = $this->productPriceAmount;

        if ($this->discount) {
            switch ($this->discount['type']) {
                case 'P':
                    $this->discountAmount = round(($this->productPriceAmount / 100) * $this->discount['value'], 2);
                    break;
                case 'FX':
                default:
                    if ($this->discount['value'] > $this->productPriceAmount) {
                        $this->discountAmount = 0;
                        $this->error[] = 'Discount not available';
                    } else {
                        $this->discountAmount = $this->discount['value'];
                    }
                    break;
            }
            $total = bcsub($total, $this->discountAmount, 2);
        }

        if ($this->tax) {
            $this->taxAmount = round(($total / 100) * $this->tax, 2);
            $total = bcadd($total, $this->taxAmount, 2);
        }



        return $total;
    }
}