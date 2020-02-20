<?php


namespace App\Http\Controllers;

use App\Library\Basket;
use App\Library\Product;

class HomeController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $RO1 = new Product('Red Widget', 'R01', 32.95);
        $G01 = new Product('Green Widget', 'G01', 24.95);
        $B01 = new Product('Blue Widget', 'B01', 7.95);

        $basket1 = new Basket();
        $basket1->add($B01)->add($G01);


        $basket2 = new Basket();
        $basket2->add($RO1)->add($RO1);

        dd(
            $basket1->calculate(),
            $basket2->calculate()
        );

        return view('home');
    }
}
