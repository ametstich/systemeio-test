<?php

namespace App\Data;
use Exception;
use stdClass;

class ProductListData extends BaseData
{
    public function createData(): void
    {
        $product1 = new stdClass();
        $product2 = new stdClass();
        $product3 = new stdClass();
        $product1->id = 1;
        $product1->name = 'Iphone';
        $product1->price = 100;
        $product2->id = 2;
        $product2->name = 'Наушники';
        $product2->price = 20;
        $product3->id = 3;
        $product3->name = 'Чехол';
        $product3->price = 10;
        $this->data = [1 => $product1, 2 => $product2, 3 => $product3];
    }

    /**
     * @throws Exception
     */
    public function find($index)
    {
        if (isset($this->data[$index]) && $product = $this->data[$index]) {
            return $product;
        }

        throw new Exception('Product not found');
    }
}