<?php

namespace App\Console\Commands;

use App\Basket;

use App\Repository\BasketRepository;
use Illuminate\Console\Command;

class BasketList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basket:list {--basket=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List a basket';
    /**
     * @var BasketRepository
     */
    private $basketRepository;

    /**
     * Create a new command instance.
     *
     * @param BasketRepository $basketRepository
     */
    public function __construct(BasketRepository $basketRepository)
    {
        parent::__construct();

        $this->basketRepository = $basketRepository;
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

        if (!$basket) {
            $this->error('The specified basket does not exist.');

            return;
        }

        if (!$basket->products()->count()) {
            $this->error('The specified basket is empty.');

            return;
        }

        $codes = $this->basketRepository->codes($basket);
        $subTotal = $this->basketRepository->subTotal($basket);
        $discount = $this->basketRepository->discount($basket);
        $delivery = $this->basketRepository->delivery($subTotal - $discount);
        $total = ($subTotal - $discount) + $delivery;

        $this->info('-------------------------');
        $this->info('Basket #' . $basket->id);
        $this->info('-------------------------');
        $this->info('Products: ' . $codes);
        $this->info('Subtotal: ' . $subTotal);
        $this->info('Delivery: ' . $delivery);
        $this->info('Discount: ' . $discount);
        $this->info('Total: ' . $total);
        $this->info('-------------------------');
    }
}
