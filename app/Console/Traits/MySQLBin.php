<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.09.2018
 * Time: 16:15
 */

namespace App\Console\Traits;


trait MySQLBin
{
    protected function getExecutor($name = "mysql")
    {
        switch (mb_strtolower(substr(PHP_OS, 0, 3))) {
            case 'win':
                if (!file_exists('C:\xampp\mysql\bin\mysql.exe')) {
                    return null;
                }
                return "C:\xampp\mysql\bin\.$name.exe";
                break;
            case 'lin':
                if (empty(exec("which $name"))) {
                    return null;
                }
                return $name;
                break;
        }
        return $name;
    }
}