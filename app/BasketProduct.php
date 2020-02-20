<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasketProduct extends Model
{
    protected $fillable = [
      'basket_id', 'product_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'id');
    }
}
