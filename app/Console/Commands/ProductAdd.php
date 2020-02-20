<?php

namespace App\Console\Commands;

use App\Basket;
use App\BasketProduct;
use App\Repository\BasketProductRepository;
use App\Repository\BasketRepository;
use App\Repository\ProductRepository;
use Illuminate\Console\Command;

class ProductAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:add {--products=} {--basket=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add products to a basket';

    /**
     * @var BasketProductRepository
     */
    private $basketRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var BasketProductRepository
     */
    private $basketProductRepository;

    /**
     * Create a new command instance.
     *
     * @param BasketRepository $basketRepository
     * @param ProductRepository $productRepository
     * @param BasketProductRepository $basketProductRepository
     */
    public function __construct(BasketRepository $basketRepository, ProductRepository $productRepository, BasketProductRepository $basketProductRepository)
    {
        parent::__construct();

        $this->basketRepository = $basketRepository;
        $this->productRepository = $productRepository;
        $this->basketProductRepository = $basketProductRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $opts = $this->options();
        $basket = $this->basketRepository->find($opts['basket']);
        $products = explode(',', trim($opts['products']));

        if (!$basket) {
            $this->error('The specified basket does not exist.');

            return;
        }

        $error = false;

        foreach ($products as $code) {
            $product = $this->productRepository->findByCode($code);

            if (!$product) {
                $error = true;

                break;
            }

            $this->basketProductRepository->add($basket->id, $product->id);
        }

        if ($error) {
            $this->error('One or more of the products your specified doesn\'t exist.');

            return;
        }

        $noOfProductsAdded = count($products);

        $this->info( $noOfProductsAdded . ' products(s) have been added to the basket.');
    }
}
