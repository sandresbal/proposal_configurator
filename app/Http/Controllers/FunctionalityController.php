<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Functionality;


class FunctionalityController extends Controller
{
    //

    public function index(){
        $functionalities  = Functionality::all();
        
        return view ('list_functionalities', compact('functionalities'));
    }

    public function add()
    {
    	return view('new_functionality');
    }


    public function create(Request $request)
    {
        $functionality = new Functionality();
        $functionality->device = $request->device;
        $functionality->atom = $request->atom;
        $functionality->name = $request->name;
        $functionality->description_short = $request->description_short;
        $functionality->description_long = $request->description_long;
        $functionality->price_setup = $request->prize_setup;
        $functionality->price_monthly = $request->prize_monthly;
        $functionality->units = $request->units;
        //$functionality->payment = $request->payment;
        $bizmodels = $request->get('bizmodel');
        $functionality->save();
        if (isset($bizmodels)){
        foreach ($bizmodels as $bizmodel){
            $functionality->attachBizmodel($bizmodel);
            }
        }
        $functionality->save();
    	return redirect('/list_functionalities'); 
    }

    public function edit(Functionality $functionality)
    {
    	if (Auth::check())
        {            
                return view('edit_functionality', compact('functionality'));
        }           
        else {
             return redirect('/');
         }            	
    }

    public function update(Request $request, Functionality $functionality)
    {
    	if(isset($_POST['delete'])) {
    		$functionality->delete();
    		return redirect('/home');
    	}
    	else
    	{
            if ($request->device != 'no'){
                $functionality->device = $request->device;
            }
            if ($request->atom != 'no'){
                $functionality->atom = $request->atom;
            }
            $functionality->name = $request->name;
            $functionality->description_short = $request->description_short;            $functionality->description_long = $request->description_long;
            $functionality->price_monthly = $request->price_monthly;
            $functionality->price_setup = $request->price_setup;

            /*if ($request->payment != 'no'){
                $functionality->payment = $request->payment;
            }*/
            if ($request->atom != 'no'){
                $functionality->units = $request->units;
            }
            
            $bizmodels = $request->get('bizmodels');                    
            $bizmodels_old = $functionality->getBizmodels();
    
            if (isset($bizmodels)){
                foreach ($bizmodels_old as $biz){
                    $functionality->detachBizmodel($biz);
                }
                foreach ($bizmodels as $bizmodel){
                    $functionality->attachBizmodel($bizmodel);
                }
            }

            $functionality->save();
            return redirect('/list_functionalities'); 

    	}    	
    }
}
