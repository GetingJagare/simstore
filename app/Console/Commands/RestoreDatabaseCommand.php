<?php

namespace App\Console\Commands;

use App\Console\Traits\DatabaseParams;
use App\Console\Traits\Loggable;
use App\Console\Traits\MySQLBin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RestoreDatabaseCommand extends Command
{
    use Loggable, DatabaseParams, MySQLBin;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $logFile = __DIR__ . '/../../../storage/logs/restore_db.log';

    protected $dbParams;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->dbParams = $this->getDBParams();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (empty($mysqlExec = $this->getExecutor())) {
            echo "Error: No MySQL installed";
            return false;
        }
        if (empty($date = $this->option('date')) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
            $this->log("Invalid date format");
            return false;
        }

        $date = str_replace('-', '_', $date);
        $backupFile =  __DIR__ . "/../../../database/backups/backup_{$date}.sql";

        DB::select("DROP DATABASE {$this->dbParams['db']}");
        $this->log("db old version deleted");
        DB::select("CREATE DATABASE {$this->dbParams['db']}");
        $this->log("db recreated");

        $this->log("db importing...");
        exec("$mysqlExec -h{$this->dbParams['host']} -P {$this->dbParams['port']} -u{$this->dbParams['user']} " .
            "-p{$this->dbParams['pass']} {$this->dbParams['db']} < $backupFile");
        $this->log("db import ended");
    }
}
