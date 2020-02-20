<?php

namespace App\Console\Commands;

use App\Basket;
use App\Repository\BasketRepository;
use Illuminate\Console\Command;

class BasketEmpty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'basket:empty {--basket=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a basket';

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

        /*****************************************
         * empty the basket via the relationship
         *****************************************/
        $basket->basketProducts()->delete();

        $this->info('Basket ' . $basket->id . ' has been emptied.');
    }
}
