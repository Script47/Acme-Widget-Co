<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    public function products()
    {
        return $this->hasManyThrough(Product::class, BasketProduct::class, 'basket_id', 'id', null, 'product_id');
    }

    public function basketProducts()
    {
        return $this->hasMany(BasketProduct::class);
    }
}
