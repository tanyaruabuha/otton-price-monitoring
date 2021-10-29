<?php

namespace App\Console\Commands;

use App\Http\Controllers\PriceListUpdate\PriceListUpdateController;
use App\ProductsUpdate;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class UpdateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all products on database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \ErrorException
     * @throws GuzzleException
     */
    public function handle()
    {
        $sync = new PriceListUpdateController();
        $sync->update();
    }
}
