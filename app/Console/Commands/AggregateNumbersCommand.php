<?php

namespace App\Console\Commands;

use App\Console\Traits\Loggable;
use App\StoreGroup;
use App\Tariff;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Number;
use App\Provider;
use App\Store;

class AggregateNumbersCommand extends Command
{
    use Loggable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aggregate:numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for aggregating numbers from remote db';

    private $numbersPerPage = 1000;

    private $appRootDir = __DIR__ . '/../../../';

    protected $logFile = __DIR__ . '/../../../storage/logs/numbers_aggr.log';

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
        exec("{$this->appRootDir}artisan backup:db");

        $this->log("Numbers DB Reading started");
        $connection = DB::connection('numbers_db');
        $blockedNumbers = $connection->table('blocked')->select(['number']);
        $numbersCount = $connection->table('simstore')
            ->whereIn('number', $blockedNumbers, 'and', true)
            ->count('number');
        $this->log("Numbers DB Reading ended");

        $this->log("Local numbers saving started");
        try {
            for ($i = 1; $i <= ceil($numbersCount / $this->numbersPerPage); $i++) {
                $numbersChunk = $connection->table('simstore')
                    ->whereIn('number', $blockedNumbers, 'and', true)
                    ->limit($this->numbersPerPage)
                    ->offset(($i - 1) * $this->numbersPerPage)
                    ->get();
                foreach ($numbersChunk as $number) {
                    $phoneNumber = Number::where('value', $number->number)->first();
                    if (!$phoneNumber) {
                        $provider = Provider::where('name', $number->operator)->first();
                        if (!$provider) {
                            $provider = new Provider();
                            $provider->name = $number->operator;
                            $provider->code = $number->operatorkod;
                            $provider->save();
                        }

                        $storeGroup = StoreGroup::where('code', $number->grskladkod)->first();
                        if (!$storeGroup) {
                            $storeGroup = new StoreGroup();
                            $storeGroup->name = $number->grsklad;
                            $storeGroup->code = $number->grskladkod;
                            $storeGroup->save();
                        }

                        $store = Store::where('code', $number->skladkod)->first();
                        if (!$store) {
                            $store = new Store();
                            $store->group_id = $storeGroup->id;
                            $store->name = $number->sklad;
                            $store->code = $number->skladkod;
                            $store->save();
                        }

                        $tariff = Tariff::where('code', $number->tarifkod)->first();
                        if (!$tariff) {
                            $tariff = new Tariff();
                            $tariff->name = $number->tarif;
                            $tariff->code = $number->tarifkod;
                            $tariff->save();
                        }

                        $phoneNumber = new Number();
                        $phoneNumber->provider_id = $provider->id;
                        $phoneNumber->value = $number->number;
                        $phoneNumber->price = $number->cena ?: 0;
                        $phoneNumber->block_po = $number->blocklk;
                        $phoneNumber->store_id = $store->id;
                        $phoneNumber->tariff_id = $tariff->id;
                        $phoneNumber->save();
                    }
                }
            }
            $this->log("Local numbers saving ended");
        } catch (\Exception $e) {
            $this->log("Error occured (code {$e->getCode()}, message: {$e->getMessage()})");
        }
    }
}
