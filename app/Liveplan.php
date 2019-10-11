<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liveplan extends Model
{
    public function proposals()
    {
        return $this->hasMany('App\Proposals');
    }
}
