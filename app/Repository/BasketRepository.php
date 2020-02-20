<?php


namespace App\Repository;

use App\Basket;
use function foo\func;

class BasketRepository
{
    private $rules = [];

    public function __construct()
    {
        /********************************************************************************************
         * Define the discount/offer rules for our products, each rule needs to have the following:
         *
         *  - iteration, determines whether the callback should run on the current or next product
         *  - condition, determines if the product is viable for the discount
         *  - callback, determines what effect should happen to the price
         *
         * In a proper system this should be db driven rather than hardcoded rules to make it easier
         * to modify but this current method gives us the flexibility to define fairly complex rules.
         ********************************************************************************************/
        $this->rules[] = [
            'iteration' => 'next',
            'condition' => function ($product) {
                return $product->code === 'R01';
            },
            'callback' => function ($price) {
                return $price / 2;
            }
        ];
    }

    public function find($basket)
    {
        return Basket::find($basket) ?? false;
    }

    public function subTotal(Basket $basket)
    {
        /*********************************
         * pluck prices from the basket,
         * run a sum,
         * and format
         *********************************/
        return number_format(array_sum($basket->products()->get()->pluck('price')->toArray()), 2);
    }

    public function delivery(int $subTotal)
    {
        if ($subTotal >= 90) {
            return 0;
        } else if ($subTotal > 50) {
            return 2.95;
        } else {
            return 4.95;
        }
    }

    public function discount(Basket $basket)
    {
        $discount = 0;
        $activeRule = null;

        $basket->products()->get()->each(function ($product, $key) use (&$discount, &$activeRule) {
            /**********************************************************
             * This is here so we can run rules over the next product
             * when iteration is "next".
             **********************************************************/
            if (is_array($activeRule) && $activeRule['condition']($product)) {
                $price = $activeRule['callback']($product->price);

                $discount += $price;
            }

            /*************************************************************
             * Loop through the rules and check them against each product
             *************************************************************/
            foreach ($this->rules as $rule) {
                if ($rule['condition']($product)) {
                    if ($rule['iteration'] === 'current') {
                        $price = $rule['callback']($product->price);

                        $discount += $price;
                    } else {
                        /******************************************************************
                         * If this is true, it means that we've just applied a rule is
                         * from the previous iteration e.g. R01, R01, R01. The rule is:
                         * “buy one red widget, get the second half price”.
                         * therefore the second R01 will not trigger the discount rule
                         * for the third so we don't set an active rule.
                         ******************************************************************/
                        if (is_array($activeRule)) {
                            $activeRule = null;
                        } else {
                            $activeRule = $rule;
                        }

                        break;
                    }
                }
            }
        });

        return number_format($discount, 2);
    }

    public function codes(Basket $basket)
    {
        /***************************
         * Pluck the product codes,
         * separate by commas
         **************************/
        return implode(', ', $basket->products()->get()->pluck('code')->toArray());
    }
}
