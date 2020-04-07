<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Number
 * @package App
 *
 * @property int $provider_id
 * @property int $id
 */
class Number extends Model
{
    //protected $connection = 'partner';
    protected $table = 'number';
    public $timestamps = false;

    public function store()
    {
        return $this->belongsTo('\App\Store');
    }

    public function region()
    {
        return $this->belongsTo('\App\Region');
    }

    public function provider()
    {
        return $this->belongsTo('\App\Provider');
    }
}
