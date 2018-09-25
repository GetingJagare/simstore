<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.09.2018
 * Time: 15:25
 */

namespace App\Console\Traits;


trait Loggable
{
    private function log($message)
    {
        if (!file_exists($this->logFile)) {
            $fHandler = fopen($this->logFile, 'w+');
            fclose($fHandler);
        }

        $dateTime = date('d-m-Y H:i:s');
        exec("echo \"$message at $dateTime\" >> {$this->logFile}");
    }
}