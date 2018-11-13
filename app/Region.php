<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public function writeToSession()
    {
        session(['region' => [
            'id' => $this->id,
            'name' => $this->name,
            'name_pr' => $this->name_pr,
            'name_dat' => $this->name_dat,
            'city' => $this->city,
            'subdomain' => $this->subdomain,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]]);
    }
}
