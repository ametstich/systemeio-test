<?php

namespace App\Data;

use Exception;

class CountryTaxListData extends BaseData
{
    public function createData(): void
    {
        $this->data = [
            'DE' => 19,
            'IT' => 22,
            'GR' => 24,
            'FR' => 20
        ];
    }

    /**
     * @throws Exception
     */
    public function find($index)
    {
        if (!$countryCode = substr($index, 0, 2)) {
            throw new Exception('Tax number not valid');
        }
        if (isset($this->data[$countryCode]) && $tax = $this->data[$countryCode]) {
            return $tax;
        } else {
            throw new Exception('Tax number not found');
        }
    }
}