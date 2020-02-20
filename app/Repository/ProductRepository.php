<?php


namespace App\Repository;

use App\Product;

class ProductRepository
{
    public function findByCode($code)
    {
        return Product::where('code', $code)->first() ?? false;
    }
}
