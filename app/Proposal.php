<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    /*public function cdn()
    {
        return $this->hasOne('App\Cdn');
    }*/

    public function cdn()
    {
        return $this->belongsTo('App\Cdn');
    }

    public function liveplan()
    {
        return $this->hasOne('App\Liveplan');
    }

    public function functionalities()
    {
        return $this->belongsToMany('App\Functionality', 'proposal_functionalities', 'proposal_id', 'functionality_id');
    }

    public function devices()
    {
        return $this->belongsToMany('App\Device', 'proposal_devices', 'proposal_id', 'device_id');
    }


    public function attachDevice($device) {
        if (is_object($device)) {
            $device = $device->getKey();
        }
        if (is_array($device)) {
            $device = $device['id'];
        }
        $this->devices()->attach($device);
    }

    public function attachFunctionality($functionality) {
        //dd($functionality);
        if (is_object($functionality)) {
            $functionality = $functionality->getKey();
        }
        if (is_array($functionality)) {
            $functionality = $functionality['id'];
        }
        $this->functionalities()->attach($functionality);
    }
}
