<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bizmodel extends Model
{
    public function functionalities()
    {
        return $this->belongsToMany('App\Functionality', 'functionality_bizmodel', 'bizmodel_id', 'functionality_id');
    }

    
}
