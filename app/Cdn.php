<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cdn extends Model
{

    public function proposals()
    {
        return $this->hasMany('App\Proposals');
    }
}
