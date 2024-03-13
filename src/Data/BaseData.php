<?php

namespace App\Data;

abstract class BaseData
{
    protected array $data;
    public function __construct()
    {
        $this->createData();
    }

    abstract function createData();

    abstract function find(string $index);
}