<?php


namespace App\Repository;

use App\BasketProduct;

class BasketProductRepository
{
    /**
     * @param $basket_id
     * @param $product_id
     */
    public function add($basket_id, $product_id)
    {
        BasketProduct::create([
            'basket_id' => $basket_id,
            'product_id' => $product_id
        ]);
    }
}
