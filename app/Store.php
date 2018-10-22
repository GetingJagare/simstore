<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    //protected $connection = 'partner';
    protected $table = 'store';
    public $timestamps = false;

    public function group()
    {
        return $this->belongsTo('\App\StoreGroup');
    }
}
