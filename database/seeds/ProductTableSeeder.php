<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'name' => 'Red Widget',
                'code' => 'R01',
                'price' => 32.95
            ],
            [
                'name' => 'Green Widget',
                'code' => 'G01',
                'price' => 24.95
            ],
            [
                'name' => 'Blue Widget',
                'code' => 'B01',
                'price' => 7.95
            ]
        ];

        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
