<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link href="{{ public_path('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

</head>
<body>
<div class="container">
<!--<form action="/proposal/{{$proposal->id}}" method="post">-->
    <h5>Propuesta: {{$proposal->receiver}}</h5>
    <h5>Compañía: {{$proposal->company}}</h5>
    <h5>Destinatario: {{$proposal->receiver}}</h5>
    <h5>Autor: {{$proposal->creator}}</h5>
    @if (!(empty($proposal->description))) 
    <h5>Descripcion: {{$proposal->description}}</h5>
    @endif
    <table id='table-personal' class="table table-bordered">
       <thead>
       <tr>
            <th>Concepto</th>
            <th>ATOM</th>
            <th>Extra</th>
            <th>Montly cost</th>
            <th>Set-up cost</th>
        </tr>
       </thead>
       <tbody>
        <tr>
            <td>
                <h5>CDN, Infraestructura y Delivery:</h5>
                <p>
                CDN con plan ATOM {{$cdn->name}}, con las siguientes características:
                <ul>
                <li>{{$cdn->capacity}} de capacidad
                </li>
                <li>Concurrencia hasta {{$cdn->concurrency}} usuarios
                </li>
                <li>Encoding {{$cdn->encoding}}
                </li>
                </ul>
                </p>
            </td>
            <td>
            <td>
            </td>
            <td>
                @if ($cdn->payment == 'Monthly fee')
                {{$cdn->price}}                
                @endif
            </td>
            <td>
                @if ($cdn->payment == 'Set-up')
                {{$cdn->price}}                
                @endif
            </td>
        </tr>
        @foreach ($devices_unique as $device)
            <tr id="name_device" style="background-color:grey">
                <td>
                <h5><b>{{$device}}</b></h5> 
                <ul>
                @switch($device) 
                    @case('Aplicaciones móviles')
                    @foreach ($mov_devices as $mov_device)
                    <li>{{$mov_device}}</li>
                    @endforeach
                    @break
                    @case('Aplicaciones de TV conectada')
                    @foreach ($tv_devices as $tv_device)
                    <li>{{$tv_device}}</li>
                    @endforeach               
                    @break
                @endswitch
                </ul>
                </td>
                <td>   </td>
                <td>   </td>
                <td>
                @switch($device) 
                    @case('Web TV')
                    {{$proposal->web_cost_monthly}} €/mes
                    @break
                    @case('Aplicaciones móviles')
                    {{$proposal->mobile_cost_monthly}} €/mes
                    @break
                    @case('Aplicaciones de TV conectada')
                    {{$proposal->tv_cost_monthly}} €/mes
                    @break
                    @case('CMS')
                    @if ($cdn->name == 'Custom');
                    {{$proposal->CMS_cost_monthly}} €/mes
                    @endif
                @endswitch
                </td>
                <td>  
                </td>
            </tr>
                @foreach ($func_obj as $func)
                    @if($func->device == $device )
                        <tr id="funct">
                            <td>
                                Nombre: {{$func->name}}<br>
                                Descripción: {{$func->description_short}}<br>
                            </td>
                            <td>
                                @if ($func->atom == 'base')
                                <input type="checkbox" name ="final_features[]" value='{{$func->id}}' onclick="return false;" checked/>
                                @endif
                            </td>
                            <td>
                                @if ($func->atom == 'extra')
                                <input type="checkbox" name ="final_features[]" value='{{$func->id}}'onclick="return false;" checked/>
                                @endif
                            </td>
                            <td>
                                @if ($func->payment == 'Monthly fee')
                                <div class='{{$func->id}}-m'> {{$func->price}} </div>
                                @endif
                            </td>
                            <td>
                                @if ($func->payment == 'Set-up')
                                <div class='{{$func->id}}-s'>{{$func->price}} </div>        
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            <tr id="price_total_per_device">
                <td>Precio total de {{$device}} + funcionalidades añadidas:</td>
                <td>  </td>
                <td>  </td>
                <td>
                @switch($device) 
                    @case('Web TV')
                    {{$proposal->web_cost_monthly + $proposal->CMS_costs_extras_monthly}} €/mes
                    @break
                    @case('Aplicaciones móviles')
                    {{$proposal->mobile_cost_monthly + $proposal->mobile_extras_cost_monthly}} €/mes
                    @break
                    @case('Aplicaciones de TV conectada')
                    {{$proposal->tv_cost_monthly + $proposal->tv_extras_cost_monthly}} €/mes
                    @break
                    @case('CMS')
                    @if ($cdn->name == 'Custom');
                    {{$proposal->CMS_cost_monthly}} €/mes
                    @endif
                @endswitch
                </td>
                <td>
                @switch($device) 
                    @case('Web TV')
                    {{$proposal->web_extras_cost_setup}} €/mes
                    @break
                    @case('Aplicaciones móviles')
                    {{$proposal->mobile_extras_cost_setup}} €/mes
                    @break
                    @case('Aplicaciones de TV conectada')
                    {{$proposal->tv_extras_cost_setup}}
                    @break
                    @case('CMS')
                    {{$proposal->CMS_cost_extras_setup}}
                @endswitch
                </td>
            </tr>
        
        @endforeach
        <tr>
            <td><h5>Total:</h5></td>
            <td>  </td>
            <td>  </td>
            <td>
                <div id="monthly-total" >
                {{$proposal->monthly_cost_total}}</div>
            </td>
            <td>
                <div id="setup-total">
                {{$proposal->set_up_cost_total}}</div>
            </td>
        </tr>
       </tbody>
     </table>
    
    @csrf
    <!--<div class="form-group">
        <button type="submit" class="btn btn-primary" id="draft" value="{{ csrf_token() }}">Crear propuesta final</button>
        
    </div>
    </form>-->
</div>

</body>