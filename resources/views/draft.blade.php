@extends('layouts.app')

@section('content')
<div class="container">
<form action="/proposal/{{$proposal->id}}" method="post">
    <h4>Propuesta: {{$proposal->receiver}}</h4>
    <h4>Compañía: {{$proposal->company}}</h4>
    <h4>Destinatario: {{$proposal->receiver}}</h4>
    <h4>Autor: {{$proposal->creator}}</h4>
    @if (!(empty($proposal->description))) 
    <h4>Descripcion: {{$proposal->description}}</h4>
    @endif
    <table id='table-personal' class="table table-bordered">
       <thead>
        <!--<th>Name</th>-->
        <th>Concepto</th>
        <th>Extra</th>
        <th>Montly cost</th>
        <th>Set-up cost</th>
       </thead>
       
       <tbody>
        <tr id ="cdn_specs">
            <td>
                <h4>CDN, Infraestructura y Delivery:</h4>
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
            <td></td>
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
            <tr id="device">
            
                <td>
                <h4>{{$device}}</h4> 
                @if ($device != 'Web TV' && $device != 'CMS')
                Sistemas operativos:
                    @if ($device == 'Aplicaciones móviles')
                    <ul>
                        @foreach ($mov_devices as $mov_device)
                        <li>{{$mov_device}}</li>
                        @endforeach
                    </ul>
                    @elseif ($device == 'Aplicaciones de TV conectada')
                    <ul>
                        @foreach ($tv_devices as $tv_device)
                        <li>{{$tv_device}}</li>
                        @endforeach   
                    </ul>  
                    @endif
                @endif
                <h5>Funcionalidades incluidas en ATOM:</h5>
                    <ul>
                    @foreach ( $group_of_functionalities_selected as $func)
                        @if($func->device == $device && $device == 'CMS' && $func->atom == 'base')
                        <br>
                        <li><b>{{$func->name}}</b><br>
                        {{$func->description_short}}<br></li>
                        @elseif($func->device == $device && $device != 'CMS' && $func->atom == 'base')
                        <li><b>{{$func->name}}</b><br>
                        {{$func->description_short}}</li>
                        @endif 
                    @endforeach
                    </ul>
                </td>
                <td></td>
                <td>
                @switch($device) 
                    @case('Web TV')
                    {{$price_web_month}} €/mes
                    @break
                    @case('Aplicaciones móviles')
                    {{$price_mov_month}} €/mes
                    @break
                    @case('Aplicaciones de TV conectada')
                    {{$price_tv_month}} €/mes
                    @break
                @endswitch
                </td>
                <td></td>
            </tr>        
            @foreach ( $group_of_functionalities_selected as $func)
                @if($proposal->package == 'extra')
                    @if($func->device == $device && $func->atom == 'extra')
                        <tr id="functionality_spec">
                        <td>
                            <b>{{$func->name}}</b><br>
                            {{$func->description_short}}<br>
                    
                        </td>
                        <td>
                            @if ($func->atom == 'extra')
                            <input type="checkbox" name ="final_features[]" value='{{$func->id}}'/>
                            @endif
                        </td>
                        <td>
                            @if ($func->price_monthly > 0)
                            <div class='{{$func->id}}-m'>{{$func->price_monthly}}</div>
                            @endif
                        </td>
                        <td>
                            @if ($func->price_setup > 0)
                                @if ($func->device == 'Aplicaciones móviles')
                                {{$func->price_setup}}€/sistema operativo. Hay {{$proposal->num_mov_dev}} seleccionados. En total serían <div class='{{$func->id}}-s'>{{$func->price_setup * $proposal->num_mov_dev}} €</div>
                                @elseif ($func->device == 'Aplicaciones de TV conectada')
                                {{$func->price_setup}}€/sistema operativo. Hay {{$proposal->num_tv_dev}} seleccionados. En total serían <div class='{{$func->id}}-s'>{{$func->price_setup * $proposal->num_tv_dev}} € </div>
                                @else 
                                <div class='{{$func->id}}-s'>{{$func->price_setup}} </div>
                                @endif
                            @endif     
                        </td>
                        </tr>
                    @endif
                @elseif($proposal->package == 'customizated')
                    @if($func->device == $device && $func->atom != 'base')
                    <tr id="functionality_spec">
                    <td>
                            <b>{{$func->name}}</b><br>
                            {{$func->description_short}}<br>
                    
                    </td>
                    <td>
                        @if ($func->atom != 'base')
                        <input type="checkbox" name ="final_features[]" value='{{$func->id}}'/>
                        @endif
                    </td>
                    <td>
                        @if ($func->price_monthly > 0)
                        <div class='{{$func->id}}-m'>{{$func->price_monthly}}</div>
                        @endif
                    </td>
                    <td>
                        @if ($func->price_setup > 0)
                            {{$func->price_setup}}€
                            @if ($func->device == 'Aplicaciones móviles')
                            /sistema operativo. Hay {{$proposal->num_mov_dev}} seleccionados. En total serían <div class='{{$func->id}}-s'>{{$func->price_setup * $proposal->num_mov_dev}} €</div>
                            @elseif ($func->device == 'Aplicaciones de TV conectada')
                            /sistema operativo. Hay {{$proposal->num_tv_dev}} seleccionados. En total serían <div class='{{$func->id}}-s'>{{$func->price_setup * $proposal->num_tv_dev}} € </div>
                            @else 
                            <div class='{{$func->id}}-s'>{{$func->price_setup}} </div>
                            @endif
                        @endif     
                    </td>
                    </tr>
                    @endif
             @endif
            @endforeach
        @endforeach
        @if($liveplan != 'no')
        <tr>
            <td>
                <h4>Herramienta VOD2Live-Live2VOD</h4>
                <p>
                El plan {{$liveplan->name}} tiene las siguientes características:
                <ul>
                <li>Ingesta de hasta {{$liveplan->channels}} canales.
                </li>
                <li>{{$liveplan->capacity}} de capacidad.
                </li>
                <li>Concurrencia hasta {{$liveplan->concurrency}} usuarios.
                </li>
                </ul>
                El CMS incluirá las siguientes funcionalidades:
                <ul>
                @foreach($funcs_live as $f)
                    <li>{{$f->name}}
                    </li>
                @endforeach
                </ul>
                </p>
            </td>
            <!--<td></td>-->
            <td></td>
            <td>
                @if ($liveplan->payment == 'Monthly fee')
                {{$liveplan->price}}€
                @endif
            </td>
            <td>
                @if ($liveplan->payment == 'Set-up')
                {{$liveplan->price}}€
                @endif
            </td>
        </tr>
        @endif
        @if($proposal->soporte24 != 0)
        <tr>
            <td><h4>Soporte 24x7 para incidencias</h4></td>
            <!--<td></td>-->
            <td></td>
            <td>{{$proposal->soporte24_cost_monthly}}€</td>
            <td></td>
        </tr>
        @endif
        <tr id="row precio definición">
            <td><h4>Total según definición:</h4></td>
            <!--<td></td>-->
            <td></td>
            <td>
            <div id="monthly-total" >
            {{$proposal->monthly_cost_total}}</div>€
            </td>
            <td>
            <div id="setup-total">
            {{$proposal->setup_cost_total}}</div>
            </td>
        </tr>
        @if($proposal->package != 'base' )
        <tr id="row precio modificado">
            <td><h4>Total recalculado:</h4></td>
            <td></td>
            <td>
                <div id="monthly-total-recal" ></div>
            </td>
            <td>
                <div id="setup-total-recal"></div>
            </td>
        </tr>
        @endif
       </tbody>
     </table>
    
    @csrf
     <div class="form-group">
        <button type="submit" class="btn btn-primary" id="draft" value="{{ csrf_token() }}">Crear propuesta final</button>
    </div>
    </form>
</div>

<script type="text/javascript">

$(document).ready(function () {
    var month = $('#monthly-total').html();
    var month_string = month.toString();
    var month_initial = parseFloat(month_string);

    var total_month_add = $('#month-func-price').html();

    var adds = $('.month-func-price').val();
    console.log("hola" + adds);

    $('input[type=checkbox]').change(function () {

        var selected_ids = [];
        var prize_month_array =[];
        var prize_setup_array =[];
        var prize_month_total = 0;
        var prize_setup_total = 0;

        console.log(selected_ids);

        $("input:checkbox:checked").each(
        
            function() {
            console.log("El checkbox con valor " + $(this).val() + " está seleccionado");
                
            var val = $(this).val();
            selected_ids.push(val);

            var prize_m = $('.' + val + '-m').map(function(){
                return $.trim($(this).text())}).get();;
            
            console.log("mensual total " + prize_m);

            if (prize_m != ''){
                console.log(" hay precio mensual ");
                prize_month_array.push(prize_m);
            }
            
            var prize_s = $('.' + val + '-s').map(function(){
                return $.trim($(this).text())}).get();;

            console.log("setup total "+ prize_s);

            if (prize_s != ''){
                console.log(" hay precio setup ");
                prize_setup_array.push(prize_s);
            }

            console.log("array precios setup" + prize_setup_array);
        })

        for (i = 0; i < prize_month_array.length; i++){
            console.log("precio mes " + prize_month_array[i]);
            console.log("precio mes parseado " + parseFloat(prize_month_array[i]))
            var prize_num =parseFloat(prize_month_array[i]);
            prize_month_total = prize_num + prize_month_total;
            console.log("precio mes TOTAL " + prize_month_total);

        }

        for (i = 0; i < prize_setup_array.length; i++){
            console.log("precio setup " +  prize_setup_array[i]);
            var prize_num =parseFloat(prize_setup_array[i]);
            prize_setup_total = prize_num + prize_setup_total;
            console.log("precio setup TOTAL " + prize_setup_total);

        }

        var total_m = prize_month_total +month_initial;
        var round_total_m = total_m.toFixed();
        var round_total_s = prize_setup_total.toFixed();
        $('#monthly-total-recal').html(round_total_m + '€/mes');
        $('#setup-total-recal').html(round_total_s + ' €');

    });

    $
})

</script>

@stop