<?php

namespace App\Console\Commands;

use App\Models\ApiData;
use App\Models\GetDataFromApi;
use Illuminate\Console\Command;

class GetDataProviderCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:getcsv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from api';

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
     */
    public function handle()
    {

        $file = realpath(realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../..') . '/FILES/csv2.csv');

        try {
            $api = new GetDataFromApi($file);
        } catch (Exception $e) {
            return;
        }
        foreach ($api->GetData() as $order) {
            ApiData::insert($order);
        }

    }
}
