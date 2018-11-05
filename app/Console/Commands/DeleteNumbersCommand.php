<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteNumbersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:numbers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $appDir = __DIR__ . '/../../../';

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
    	exec("php {$this->appDir}artisan backup:db");
        DB::table('number')->truncate();
    }
}
