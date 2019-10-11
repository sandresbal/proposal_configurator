<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

    public function functionalities()
    {
        return $this->hasMany('App\Functionality');
    }

    public function proposals()
    {
        return $this->belongsToMany('App\Proposal', 'proposal_devices', 'device_id', 'proposal_id');
    }

}
