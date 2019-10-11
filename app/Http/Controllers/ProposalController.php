<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bizmodel;
use App\Device;
use App\Proposal;
use App\Cdn;
use App\Functionality;
use App\Liveplan;

use DB;
use Log;
use PDF;
use PDF_styled;
use Auth;

class ProposalController extends Controller
{
    public function index(){
        $bizmodels = Bizmodel::all();
        $devices = Device::all();
        $cdns = Cdn::all();
        $liveplans = Liveplan::all();

        return view ('configure_proposal', compact('bizmodels', 'devices','cdns', 'liveplans'));
    }

    public function list(){
        $proposals = Proposal::where('status', 'final')->get();
        return view ('list_proposals', compact('proposals'));
    }

    public function delete(Proposal $proposal){
        $proposal->delete();
        return redirect('/home');
    }

    public function viewProposal(Proposal $proposal){

        $func_obj = $proposal->functionalities;
        $functionalities_all = Functionality::all();

        $devices = 
        $devices =[];
        foreach ($proposal->devices as $dev){
            array_push($devices, $dev->id);
        }
        if (isset($devices)){
            $devices_unique = self::findDevicesUnique($devices);
        } else {
            $devices_unique = [];
        }

        $cdn = Cdn::find($proposal->cdn);

        $live_id= 'null';
        $liveplan = 'null';

        if($proposal->liveplan != null){
            $live_id = $proposal->liveplan;
            $liveplan = DB::table('liveplans')->where('id', $live_id)->first();
            $funcs_live = Functionality::where('device', 'live')->get();
        } else {
            $liveplan = 'no';
            $funcs_live = [];
        }

        $dev_ids = [];
        $devices_names = [];
        
        foreach ($devices as $device){
            $dev = Device::find($device);
            array_push($dev_ids, $dev->id);
        }
        foreach ($devices as $device){
            $dev = Device::find($device);
            array_push($devices_names, $dev->name);
        }
        
        $mov_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones móviles");
        $tv_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones de TV conectada");
        $num_mov_devices = count($mov_devices);
        $num_tv_devices = count($tv_devices);

        return view ('proposal', compact('proposal','func_obj', 'devices_unique', 'cdn',  
        'mov_devices', 'tv_devices', 'liveplan', 'funcs_live','num_mov_devices', 'num_tv_devices', 'functionalities_all'));
    }

    public function updateDetailsProposal (Request $request, Proposal $proposal){
        
        self::createDraft($request, $proposal);
        $devices = $request->devices;

        //dd($devices);
        
        if (isset($devices)){
            $devices_unique = self::findDevicesUnique($devices);
        } else {
            $devices_unique = [];
        }

        $dev_ids = [];
        $devices_names = [];

        if(isset($devices)){
            foreach ($devices as $device){
                $dev = Device::find($device);
                array_push($dev_ids, $dev->id);
            }
            foreach ($devices as $device){
                $dev = Device::find($device);
                array_push($devices_names, $dev->name);
            }
        }

        $mov_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones móviles");
        $tv_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones de TV conectada");

        if (isset($devices)){
            $price_web_month = self::calculatePrice($devices, 'Web TV', 1);
            $quantity_mov = self::calculateQuantity($devices, 'Aplicaciones móviles');
            $price_mov_month = self::calculatePrice($devices, 'Aplicaciones móviles', $quantity_mov);
            $quantity_tv = self::calculateQuantity($devices, 'Aplicaciones de TV conectada');
            $price_tv_month = self::calculatePrice($devices, 'Aplicaciones de TV conectada', $quantity_tv);
            }
        
        $group_of_functionalities_selected = [];
    
        if (isset($devices)){
                $group_of_functionalities_selected = self::groupOfFunctionalitiesSelected($devices, $proposal->bizmodel, $devices_unique, $proposal->package);
            }

        $cdn = Cdn::find($proposal->cdn);

        if ($proposal->liveplan != null){
            $liveplan = Liveplan::find($proposal->liveplan);
            $funcs_live = Functionality::where('device', 'live')->get();
        } else {
            $liveplan = 'no';
            $funcs_live = [];
        }

        $package = $proposal->package;

        return view ('draft', compact('proposal', 'package', 'cdn', 'group_of_functionalities_selected', 'devices_unique', 'mov_devices', 'tv_devices', 'liveplan', 'funcs_live'));

    }

    public function edit(Proposal $proposal){
    	if (Auth::check())
        {            
            $bizmodels = Bizmodel::all();
            $devs = Device::all();
            $cdns = Cdn::all();
            $liveplans = Liveplan::all();
            $proposal_devices = $proposal->devices;

            $package = $proposal->package;

            $pack_name= '';
            if($package == 'base'){
                $pack_name = 'ATOM';
            }
            else if ($package == 'extra') {
                $pack_name = 'ATOM con extras';
            };

            $live_name = '';
            if ($proposal->liveplan != null){
                $live = Liveplan::find($proposal->liveplan);
                $live_name = $live->name;
            } else {
                $live_name ="No quiere herramienta de directos";
            }

            $cdn = Cdn::find($proposal->cdn);
            $cdn_name = $cdn->name;

            $bizmodel = Bizmodel::find($proposal->bizmodel); 
            
            return view('edit_proposal', compact('proposal', 'bizmodels', 'pack_name','devs', 'cdns', 'proposal_devices', 'cdn_name', 'bizmodel', 'live_name', 'liveplans'));
        }           
        else {
             return redirect('/');
         }       
    }

    public function createProposal(Request $request, Proposal $proposal){
        
        $proposal->status="final";
        $array_func_draft = $proposal->functionalities;
        foreach($array_func_draft as $fun){
            $proposal->functionalities()->detach($fun);
        }

        $group_of_functionalities_selected = $request->get('final_features');
        $func_obj = [];

        if(isset($group_of_functionalities_selected)){
            foreach ($group_of_functionalities_selected as $func){
                $f = DB::table('functionalities')->where('id', $func)->first();
                array_push($func_obj, $f);
            }
            foreach($group_of_functionalities_selected as $funcionality_extra){
                $proposal->functionalities()->attach($funcionality_extra);
            }
        }
        $functionalities_all = Functionality::all();


        $cdn_id = $proposal->cdn;
        $cdn = DB::table('cdns')->where('id', $cdn_id)->first();
        $live_id= 'null';
        $liveplan = 'null';

        if(isset($proposal->liveplan)){
            $live_id = $proposal->liveplan;
            $liveplan = DB::table('liveplans')->where('id', $live_id)->first();
            $funcs_live = Functionality::where('device', 'live')->get();
        } else {
            $funcs_live =[];
        }


        $devices = $proposal->devices;
        $dev_ids = [];
        
        $devices_names = [];

        foreach ($devices as $device){
            $dev = DB::table('devices')->where('id', $device->id)->first();
            array_push($dev_ids, $dev->id);
        }

        foreach ($devices as $device){
            $dev = DB::table('devices')->where('id', $device->id)->first();
            array_push($devices_names, $dev->name);
        }

        $mov_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones móviles");
        $tv_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones de TV conectada");
        $num_mov_devices = count($mov_devices);
        $num_tv_devices = count($tv_devices);

        $devices_unique = array_unique($devices_names);//me da los dispositivos únicos

        if (isset($group_of_functionalities_selected)){
            self::savefinalCosts($proposal, $num_mov_devices, $num_tv_devices, $group_of_functionalities_selected);
        }

        $price_cdn_month = $proposal->cdn_cost_monthly;
        $price_cms_month = $proposal->CMS_cost_monthly;
        $price_web_month = $proposal->web_cost_monthly;
        $price_mobile_month = $proposal->mobile_cost_monthly;
        $price_tv_month = $proposal->tv_cost_monthly;

        $price_cdn_setup = $proposal->cdn_cost_setup;
        $price_cms_setup = $proposal->CMS_cost_extras_setup;
        $price_web_setup = $proposal->web_extras_cost_setup;
        $price_mobile_setup = $proposal->mobile_extras_cost_setup;
        $price_tv_setup = $proposal->tv_extras_cost_setup;
        $price_live_month = $proposal->price_live_month;

        $price_cms_extras_monthly = $proposal->CMS_cost_extras_monthly;
        $price_web_extras_monthly = $proposal->web_extras_cost_monthly;
        $price_mobile_extras_monthly = $proposal->mobile_extras_cost_monthly;
        $price_tv_extras_monthly = $proposal->tv_extras_cost_monthly;

        Log::info('antes del return view');
        $proposal->save();

        return view ('proposal', compact('proposal','func_obj', 'devices_unique', 'cdn',  
        'price_cms_month', 'price_cms_extras_monthly', 'price_cms_setup',
        'price_cdn_month', 'price_cdn_setup', 
        'price_web_month', 'price_web_extras_monthly', 'price_web_setup', 
        'price_mobile_month', 'price_mobile_extras_monthly', 'price_mobile_setup', 
        'price_tv_month', 'price_tv_extras_monthly', 'price_tv_setup','mov_devices', 'tv_devices', 'liveplan', 'funcs_live','num_mov_devices', 'num_tv_devices', 'functionalities_all'));

    }

    public function savefinalCosts(Proposal $proposal, int $num_mov_devices, int  $num_tv_devices, Array $funcionalities_extra){

        $cdn_cost_monthly = 0;

        $CMS_month_initial = $proposal->CMS_cost_monthly;
        $CMS_extras_cost_monthly = 0;;
        $CMS_extras_cost_setup = 0;

        $web_month_initial = $proposal->web_cost_monthly;
        $web_extras_cost_monthly = 0;
        $web_extras_cost_setup = 0;
        $web_total_month = 0;

        $mobile_month_initial = $proposal->mobile_cost_monthly;
        $mobile_extras_cost_monthly = 0;
        $mobile_extras_cost_setup=0;
        $mobile_total_month = 0;

        $tv_month_initial = $proposal->tv_cost_monthly;
        $tv_extras_cost_monthly = 0; 
        $tv_extras_cost_setup = 0;
        $tv_total_month = 0;

        //'customizations

        foreach($funcionalities_extra as $funcionality_extra){
            $func = DB::table('functionalities')->where('id', $funcionality_extra)->first();
            if ($func->atom == 'extra'){
                switch($func->device){
                    case ('CMS'):
                        $CMS_extras_cost_setup = $CMS_extras_cost_setup + $func->price_setup;
                        $CMS_extras_cost_monthly = $CMS_extras_cost_monthly + $func->price_monthly;
                    break;
                    case ('Web TV'):
                        $web_extras_cost_setup = $web_extras_cost_setup + $func->price_setup;
                        $web_extras_cost_monthly = $web_extras_cost_monthly + $func->price_monthly;
                    break;
                    case ('Aplicaciones móviles'):
                        $mobile_extras_cost_setup = $mobile_extras_cost_setup + ($func->price_setup  * $num_mov_devices);
                        $mobile_extras_cost_monthly = $mobile_extras_cost_monthly + ($func->price_monthly);
                    break;
                    case ('Aplicaciones de TV conectada'):
                        $tv_extras_cost_setup = $tv_extras_cost_setup + ($func->price_setup * $num_tv_devices);
                        $tv_extras_cost_monthly = $tv_extras_cost_monthly + $func->price_monthly;
                    break;
                }
            }
        }
            
            $proposal->CMS_cost_extras_monthly = $CMS_extras_cost_monthly;
            $proposal->CMS_cost_extras_setup = $CMS_extras_cost_setup;

            $proposal->web_extras_cost_monthly = $web_extras_cost_monthly;
            $proposal->web_extras_cost_setup = $web_extras_cost_setup;

            $proposal->mobile_extras_cost_monthly = $mobile_extras_cost_monthly;
            $proposal->mobile_extras_cost_setup = $mobile_extras_cost_setup;

            $proposal->tv_extras_cost_monthly =$tv_extras_cost_monthly;
            $proposal->tv_extras_cost_setup = $tv_extras_cost_setup;

            $proposal->save();

            $proposal->monthly_cost_total = 
            $proposal->cdn_cost_monthly + $proposal->CMS_cost_monthly +
                $proposal->CMS_cost_extras_monthly + $proposal->web_cost_monthly 
                +$proposal->web_extras_cost_monthly +$proposal->mobile_extras_cost_monthly
                +$proposal->mobile_cost_monthly + $proposal->tv_extras_cost_monthly
                +$proposal->tv_cost_monthly + $proposal->price_live_month + $proposal->soporte24_cost_monthly;

            Log::info('cdn: '. $proposal->cdn_cost_monthly . ' cms mes:'. $proposal->CMS_cost_monthly . ' cms extras: '.$proposal->CMS_cost_extras_monthly . ' web mes: '. $proposal->web_cost_monthly . ' web extras mes: '. $proposal->web_extras_cost_monthly . ' mobile extras mes: '. $proposal->mobile_extras_cost_monthly . ' mobile mes: '. $proposal->mobile_cost_monthly . ' tv extras mes'. $proposal->tv_extras_cost_monthly
            . ' tv mes: '. $proposal->tv_cost_monthly . ' live mes: '. $proposal->price_live_month);

            $proposal->set_up_cost_total = $CMS_extras_cost_setup + $web_extras_cost_setup + $mobile_extras_cost_setup + $tv_extras_cost_setup;
            $proposal->save();
        
    }

    public function createDraft(Request $request, Proposal $proposal){
        
        if (!isset($proposal)){
            $proposal = new Proposal();
        }

        $funcs_live = null;

        $liv = $request->live;
        if(isset($liv)){
            switch($liv){
                case ('no'):
                    $liveplan = 'no';
                    $proposal->liveplan = null;
                    $funcs_live = null;
                break;
                case ('same'):
                    if ($proposal->liveplan != 'null') {
                        $liveplan = Liveplan::find($proposal->liveplan);
                        $funcs_live = Functionality::where('device', 'live')->get();
                        
                    } else {
                        $liveplan = 'no';
                    }
                break;
                default:
                    $proposal->liveplan = $request->live;
                    $funcs_live = Functionality::where('device', 'live')->get();
                    $liveplan = Liveplan::find($proposal->liveplan);
                break;
            }
        }

        $proposal->receiver = $request->receiver;
        $proposal->creator = $request->creator;
        $proposal->description = $request->description;
        $proposal->company = $request->company;
        if ($request->cdn != 'no'){
            $proposal->cdn = $request->cdn;
            $cdn = DB::table('cdns')->where('id', $request->cdn)->first();
        } else {
            $cdn = Cdn::find($proposal->cdn);
        }
        $cdn_id = $proposal->cdn;
        $cdn_name = $cdn->name;

        if ($request->bizmodel != 'no'){
            $proposal->bizmodel = $request->bizmodel;
        } 

        $bizmodel =$proposal->bizmodel;
        
        if ($request->package != 'no'){
            $proposal->package = $request->package;
        }
        $package = $proposal->package;

        if ($proposal->status != 'final'){
            $proposal->status="draft";
        }

        $customizated = false;
        if ($request->package == 'customizated'){
            $customizated = true;
        }

        if ($request->soporte24 != '0'){
            $proposal->soporte24 = $request->soporte24;
            $proposal->soporte24_cost_monthly = $cdn->price * 2;
        }

        $proposal->save();

        $devices = [];

        $dev_aux = $request->input('devices');

        if (isset($dev_aux)){
                $devices = $dev_aux;
        } 
        
        if ($proposal->status == 'draft'){
            if (isset($devices)){
                foreach ($devices as $device){
                    $proposal->attachDevice($device);
                }
            }
        } else{
            foreach ($proposal->devices as $dev_prev){
                    $proposal->devices()->detach($dev_prev);
                }
                if (isset($devices)){
                    foreach ($devices as $device){
                        $proposal->attachDevice($device);
                    }
            }
        }

        $proposal->save(); 

        $devices_unique = self::findDevicesUnique($devices);

        $functionalities_total = Functionality::all();
        $group_of_functionalities_selected = [];


        if (isset($devices)){
            $group_of_functionalities_selected = self::groupOfFunctionalitiesSelected($devices, $bizmodel, $devices_unique, $package);
        }

        $price_cdn_month = 0;
        $price_live_month = 0;
        $cdn_custom = false;

        if ($request->cdn != 'no'){
            if ($cdn->name != 'Custom'){
                $price_cdn_month = $cdn->price;
                $price_cdn_setup = 0;
            } else {
                $cdn_custom = true;
            }
        } else {
            $price_cdn_month = $proposal->cdn_cost_monthly;
        }

        if($proposal->liveplan != null){
            $price_live_month = $liveplan->price;
        }

        $price_CMS_month = self::calculatePriceCMS($devices, $package, $cdn_name);

        $price_web_month = 0;
        $price_mov_month = 0;
        $price_tv_month = 0;
        
        $mov_devices = [];
        $tv_devices = [];
        
        if (isset($devices)){
        $price_web_month = self::calculatePrice($devices, 'Web TV', 1);

        $quantity_mov = self::calculateQuantity($devices, 'Aplicaciones móviles');
        $price_mov_month = self::calculatePrice($devices, 'Aplicaciones móviles', $quantity_mov);

        $quantity_tv = self::calculateQuantity($devices, 'Aplicaciones de TV conectada');
        $price_tv_month = self::calculatePrice($devices, 'Aplicaciones de TV conectada', $quantity_tv);
        }
        //dd($price_tv_month);

        $mov_devices = self::calculateDevicesPerType($devices, "Aplicaciones móviles");
        $tv_devices = self::calculateDevicesPerType($devices, "Aplicaciones de TV conectada");
        $num_mov_devices = count($mov_devices);
        $num_tv_devices = count($tv_devices);
        $proposal->num_mov_dev = $num_mov_devices;
        $proposal->num_tv_dev = $num_tv_devices;
        $proposal->save();

        self::saveDraftCosts($proposal, $price_cdn_month, $price_CMS_month, $price_web_month, $price_mov_month, $price_tv_month, $price_live_month);

        if($proposal->status == 'draft') {
            return view ('draft', compact('proposal', 'package', 'cdn', 'group_of_functionalities_selected', 'devices_unique', 'customizated', 'cdn_custom', 'price_cdn_month', 'price_web_month', 'price_mov_month', 'price_tv_month', 'mov_devices', 'tv_devices', 'liveplan','funcs_live' , 'num_mov_devices', 'num_tv_devices'));
        }      

    }

    public function findDevicesUnique(Array $devices){
        $devices_names = [];
        if (isset($devices)){//guardo en devices name los types
            foreach ($devices as $device){
            $dev = DB::table('devices')->where('id', $device)->first();
            array_push($devices_names, $dev->name);
            }
        } 
        return array_unique($devices_names);
    }

    public function saveDraftCosts(Proposal $proposal, float $price_cdn_month, float $price_CMS_month, float $price_web_month, float $price_mov_month, float $price_tv_month, float $price_live_month){

        Log::info('comenzamos a guardar costes del borrador '. $proposal->id);

        $proposal->cdn_cost_monthly = $price_cdn_month;
        $proposal->CMS_cost_monthly = $price_CMS_month;
        $proposal->web_cost_monthly =$price_web_month;
        $proposal->mobile_cost_monthly = $price_mov_month;
        $proposal->tv_cost_monthly = $price_tv_month;
        $proposal->price_live_month = $price_live_month;
        $proposal->monthly_cost_total = $price_cdn_month + $price_CMS_month+ $price_web_month + $price_mov_month + $price_tv_month + $price_live_month + $proposal->soporte24_cost_monthly;

        Log::info('coste cdn mes ' . $price_cdn_month . ' coste CMS mes '. $price_CMS_month . ' coste web mes ' . $price_web_month .  ' coste movil mes ' . $price_mov_month . ' coste tv mes ' . $price_tv_month . ' precio live mes '. $price_live_month . ' coste 24 horas '. $proposal->soporte24_cost_monthly);


        $proposal->save();

    }

    public function calculatePriceCMS (Array $devices, string $package, string $cdn){
        $price_CMS_month = 0;

        foreach ($devices as $dev){
            $d_obj = Device::find($dev);
            if($d_obj->name == 'CMS' && $cdn == 'Custom'){
                $price_CMS_month = 1399;
            } 
        }

        return $price_CMS_month;
        Log::info('calculamos precio CMS '. $price_CMS_month);
    }
   
    public function groupOfFunctionalitiesSelected (Array $devices, string $bizmodel, Array $devices_unique, string $package){
        $group_of_functionalities_selected = [];
        
            foreach($devices_unique as $device_unique){
                $biz = (int)$bizmodel;
                switch ($biz){
                    case (1):
                        if($package == 'base'){
                        $functions_filtered = Functionality::where('atom', $package)->where('device', $device_unique)->whereHas('bizmodels', function($q) { $q->where ('bizmodel_id', 3);})->get();
                        } elseif ($package == 'extra'){
                            $functions_filtered = Functionality::where('device', $device_unique)->whereHas('bizmodels', function($q) { $q->where ('bizmodel_id', 3);})->where(function($query) {
                                $query->where('atom', 'base')
                                    ->orWhere('atom', 'extra');})->get();
                        }
                        else {
                            //dd("seleccionando funcionalidades sin restricción");
                            $functions_filtered = Functionality::where('device', $device_unique)->whereHas('bizmodels', function($q) { $q->where ('bizmodel_id', 3);})->get();
                        }
                    break;
                    case (2):
                        if($package == 'base'){
                        $functions_filtered = Functionality::where('atom', $package)->where('device', $device_unique)->whereHas('bizmodels', function($q) { $q->where ('bizmodel_id', 2);})->get();
                        } else {
                        $functions_filtered = Functionality::where('device', $device_unique)->whereHas('bizmodels', function($q) { $q->where ('bizmodel_id', 2);})->get();  
                        }
                    break;
                    case (3):
                        if($package == 'base'){
                            $functions_filtered = Functionality::where('atom', $package)->where('device', $device_unique)->whereHas('bizmodels', function($q) { $q->where ('bizmodel_id', 3);})->get();
                        }else {
                            $functions_filtered = Functionality::where('device', $device_unique)->whereHas('bizmodels', function($q) { $q->where ('bizmodel_id', 3);})->get();
                        }
                    break;
                    }
                foreach($functions_filtered as $func){
                array_push($group_of_functionalities_selected, $func);
                Log::info('pusheamos funcionalidad filtrada');  
                }
            }

        
        //Log::info('array de funcionalidades seleccionadas '. print_r($group_of_functionalities_selected, true));
        return $group_of_functionalities_selected;

    }

    public function calculatePrice(Array $devices, string $device_name, int $number_devices){
        $price = 0;
        foreach($devices as $device){
            $dev = new Device ();
            $dev = DB::table('devices')->where('id', $device)->first();
            if ($dev->name == $device_name){
                $price = $price + $dev->price;
            }
        }
        
        return $price;
    }

    public function calculateQuantity (Array $devices, string $device_name){
        $quantity = 0;
        foreach($devices as $device){
            $dev = new Device ();
            $dev = DB::table('devices')->where('id', $device)->first();
            if ($dev->name == $device_name){
                ++$quantity;            
            }
        }
        return $quantity;
    }

    public function calculateDevicesPerType(Array $devices, string $device_name){
        $array_devices = [];
        foreach($devices as $device){
            $dev = new Device ();
            $dev = DB::table('devices')->where('id', $device)->first();
            if ($dev->name == $device_name){
                array_push($array_devices, $dev->os);
            }
        }
        return $array_devices;
    }

    public function generatePDF($proposal){

        $prop = Proposal::find($proposal);
        $cdn_id = $prop->cdn;
        $cdn = DB::table('cdns')->where('id', $cdn_id)->first();
        $proposal_devices = $prop->devices;
        $devices_names = [];
        $dev_ids = [];
        
        $devices_names = [];
        foreach ($proposal_devices as $device){
            $dev = new Device();
            $dev = DB::table('devices')->where('id', $device->id)->first();
            array_push($dev_ids, $dev->id);
        }
        foreach ($proposal_devices as $device){
            $dev = new Device();
            $dev = DB::table('devices')->where('id', $device->id)->first();
            array_push($devices_names, $dev->name);
        }

        $devices_unique = array_unique($devices_names);

        $func_obj = $prop->functionalities;

        $mov_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones móviles");
        $tv_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones de TV conectada");

        $data = [
            'proposal' => $prop,
            'cdn' => $cdn,
            'devices_unique' => $devices_unique,
            'func_obj' => $func_obj,
            'tv_devices' => $tv_devices,
            'mov_devices' => $mov_devices,
        ];
        $pdf = PDF::loadView('proposal_2', $data);
        return $pdf->download('proposal.pdf');
    }

    public function generateStyledPDF($proposal){

        //$prop = DB::table('proposals')->where('id', $proposal)->first();
        $prop = Proposal::find($proposal);
        $cdn_id = $prop->cdn;
        $cdn = DB::table('cdns')->where('id', $cdn_id)->first();
        $proposal_devices = $prop->devices;
        $devices_names = [];
        $dev_ids = [];
        
        $devices_names = [];
        foreach ($proposal_devices as $device){
            $dev = new Device();
            $dev = DB::table('devices')->where('id', $device->id)->first();
            array_push($dev_ids, $dev->id);
        }
        foreach ($proposal_devices as $device){
            $dev = new Device();
            $dev = DB::table('devices')->where('id', $device->id)->first();
            array_push($devices_names, $dev->name);
        }

        $devices_unique = array_unique($devices_names);

        $func_obj = $prop->functionalities;

        $mov_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones móviles");
        $tv_devices = self::calculateDevicesPerType($dev_ids, "Aplicaciones de TV conectada");

        $data = [
            'proposal' => $prop,
            'cdn' => $cdn,
            'devices_unique' => $devices_unique,
            'func_obj' => $func_obj,
            'tv_devices' => $tv_devices,
            'mov_devices' => $mov_devices,
        ];
        //$pdf = PDF::loadView('proposal_2', $data);
        //return $pdf->download('proposal.pdf');

        return \PDF_styled::loadView('proposal_2', $data)->download('proposal-styled.pdf');
    }
}
