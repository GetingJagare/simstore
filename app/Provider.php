<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.09.2018
 * Time: 12:24
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 * @package App
 *
 * @property string $name
 */
class Provider extends Model
{
    protected $table = 'provider';
    public $timestamps = false;
}