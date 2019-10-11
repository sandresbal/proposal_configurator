<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Functionality extends Model
{
    public function bizmodels()
    {
        return $this->belongsToMany('App\Bizmodel', 'functionality_bizmodel', 'functionality_id', 'bizmodel_id');
    }

    public function device()
    {
        return $this->belongsTo('App\Device');
    }

    public function proposals()
    {
        return $this->belongsToMany('App\Proposal', 'proposal_functionalities',  'functionality_id', 'proposal_id');
    }

    public function getBizmodels() {
        $bizmodels = [];
        if ($this->bizmodels()) {
            $bizmodels = $this->bizmodels()->get();
        }
        return $bizmodels;
    }

    public function attachBizmodel($bizmodel) {
        if (is_object($bizmodel)) {
            $bizmodel = $bizmodel->getKey();
        }
        if (is_array($bizmodel)) {
            $bizmodel = $bizmodel['id'];
        }
        $this->bizmodels()->attach($bizmodel);
    }

    public function detachBizmodel($bizmodel) {
        if (is_object($bizmodel)) {
            $bizmodel = $bizmodel->getKey();
        }
        if (is_array($bizmodel)) {
            $bizmodel = $bizmodel['id'];
        }
        $this->bizmodels()->detach($bizmodel);
    }
}
