<?php

namespace App\Console\Commands;

use App\Models\ApiData;
use App\Models\GetDataFromApi;
use Illuminate\Console\Command;

class GetDataProviderXML extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:getxml';

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

        $file = realpath(realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../..') . '/FILES/xml.xml');

        try {
            $api = new GetDataFromApi($file);
        } catch (Exception $e) {
            // Место для евента
            Log::warning(' Error new for class GetDataFromApi');
            return;
        }
        foreach ($api->GetData() as $order) {
            ApiData::insert($order);
        }

    }
}
