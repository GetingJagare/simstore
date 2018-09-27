<?php

namespace App\Console\Commands;

use App\Console\Traits\DatabaseParams;
use App\Console\Traits\Loggable;
use App\Console\Traits\MySQLBin;
use Illuminate\Console\Command;

class MakeDatabaseBackupCommand extends Command
{
    use DatabaseParams, MySQLBin, Loggable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for db backuping';

    private $backupStorage = __DIR__ . '/../../../database/backups';

    protected $logFile = __DIR__ . '/../../../storage/logs/backup.log';

    private $dbParams = [];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!is_dir($this->backupStorage)) {
            mkdir($this->backupStorage, 0775, true);
        }
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
        $this->log("DB Backuping started");
        $currentDateTime = date('Y_m_d');
        if (empty($mysqlExec = $this->getExecutor("mysqldump"))) {
            $errorMessage = "Error: No MySQL installed";
            $this->log($errorMessage, false);
            echo $errorMessage;
            return false;
        }
        $backupCommand = "$mysqlExec -h{$this->dbParams['host']} -u{$this->dbParams['user']} -p{$this->dbParams['pass']} " .
            "-P {$this->dbParams['port']} " . "{$this->dbParams['db']} " . "> " . "{$this->backupStorage}" .
            "/backup_{$currentDateTime}.sql";
        exec($backupCommand);
        $this->log("DB Backuping ended");
    }
}
