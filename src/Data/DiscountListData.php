<?php

namespace App\Data;

use Exception;
class DiscountListData extends BaseData
{
    public function createData(): void
    {
        $this->data = [
            'P1' => [
                'type' => 'P',
                'value' => 5
            ],
            'P2' => [
                'type' => 'P',
                'value' => 30
            ],
            'P3' => [
                'type' => 'P',
                'value' => 50
            ],
            'P5' => [
                'type' => 'P',
                'value' => 6
            ],
            'FX1' => [
                'type' => 'FX',
                'value' => 5
            ],
            'FX2' => [
                'type' => 'FX',
                'value' => 10
            ],
            'FX3' => [
                'type' => 'FX',
                'value' => 50
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function find($index)
    {
        if (!$index) {
            return ['type' => null, 'value'=>0];
        }
        if (!isset($this->data[$index])) {
            throw new Exception('Promo code not found');
        }

        return $this->data[$index];
    }
}