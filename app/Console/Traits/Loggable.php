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
    private function log($message, $includeDate = true)
    {
        if (!file_exists($this->logFile)) {
            $fHandler = fopen($this->logFile, 'w+');
            fclose($fHandler);
        }

        exec("echo \"$message" . ($includeDate ? " at " . date('d-m-Y H:i:s') : "") . "\" >> {$this->logFile}");
    }
}