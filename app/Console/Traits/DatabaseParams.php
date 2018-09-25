<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.09.2018
 * Time: 15:43
 */

namespace App\Console\Traits;


trait DatabaseParams
{
    protected function getDBParams()
    {
        return [
            'db' => env('DB_DATABASE'),
            'host' => env('DB_HOST'),
            'user' => env('DB_USERNAME'),
            'pass' => env('DB_PASSWORD'),
            'port' => env('DB_PORT')
        ];
    }
}