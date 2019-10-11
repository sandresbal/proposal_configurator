@extends('layouts.app')

@section('content')
<div class="container">
    <!--<form action="/proposal/{{$proposal->id}}/pdf/">
        <button type="submit" name="pdf" class="btn btn-primary">Generar pdf</button>
        {{ csrf_field() }}
    </form>
    <form action="/proposal/{{$proposal->id}}/pdf_styled/">
        <button type="submit" name="pdf" class="btn btn-primary">Generar pdf con estilos</button>
        {{ csrf_field() }}
    </form>-->
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
        <th>Concepto</th>
        <th>Extra</th>
        <th>Montly cost</th>
        <th>Set-up cost</th>
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
         <tr id="name_device" style="background-color:white">
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
                    @foreach ($functionalities_all as $func)
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

                <!--<td></td>-->
                <td>  </td>
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
                                <b>{{$func->name}}</b>
                                @if (isset($func->description_short))
                                    <br>
                                {{$func->description_short}}<br>
                                @endif
                            </td>
                            <!--<td>
                                @if ($func->atom == 'base')
                                <input type="checkbox" name ="final_features[]" value='{{$func->id}}' onclick="return false;" checked/>
                                @endif
                            </td>-->
                            <td>
                                @if ($func->atom == 'extra')
                                <input type="checkbox" name ="final_features[]" value='{{$func->id}}'onclick="return false;" checked/>
                                @endif
                            </td>
                            <td>
                                @if ($func->price_monthly > 0)
                                <div class='{{$func->id}}-m'> {{$func->price_monthly}} </div>
                                @endif
                            </td>
                            <td>
                            @if ($func->price_setup > 0)
                                <div class='{{$func->id}}-s'>{{$func->price_setup}} </div>   
                            @endif     
                            </td>
                        </tr>
                    @endif
                @endforeach
            <tr id="price_total_per_device">
                <td>Precio total de {{$device}} + funcionalidades añadidas:</td>
                <!--<td></td>-->
                <td> </td>
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
                    @if($proposal->web_extras_cost_setup >0)
                    {{$proposal->web_extras_cost_setup}} €/mes
                    @endif
                    @break
                    @case('Aplicaciones móviles')
                    @if($proposal->mobile_extras_cost_setup >0)
                    {{$proposal->mobile_extras_cost_setup}}€/mes
                    @endif
                    @break
                    @case('Aplicaciones de TV conectada')
                    @if($proposal->tv_extras_cost_setup >0)
                    {{$proposal->tv_extras_cost_setup}}
                    @endif
                    @break
                    @case('CMS')
                    @if($proposal->CMS_cost_extras_setup >0)
                    {{$proposal->CMS_cost_extras_setup}}
                    @endif
                @endswitch
                </td>
            </tr>
        
        @endforeach
        @if(isset($proposal->liveplan))
            <tr>
            <td>
                <h5>Herramienta VOD2Live-Live2VOD</h5>
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
                {{$liveplan->price}}
                @endif
            </td>
            <td>
                @if ($liveplan->payment == 'Set-up')
                {{$liveplan->price}}
                @endif
            </td>
        </tr>
        @endif
        @if($proposal->soporte24 != 0)
        <tr>
            <td><h5>Soporte 24x7 para incidencias</h5></td>
            <!--<td></td>-->
            <td></td>
            <td>{{$proposal->soporte24_cost_monthly}}€</td>
            <td></td>
        </tr>
        @endif
        <tr>
            <td><h5>Total:</h5></td>
            <!--<td></td>-->

            <td> </td>
            <td>
                {{$proposal->monthly_cost_total}}€/mes
            </td>
            <td>
            @if ($proposal->set_up_cost_total != null)
                {{$proposal->set_up_cost_total}}€
            @endif
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
            console.log("mensual " + prize_m);

            if (prize_m != ''){
                console.log(" hay precio mensual ");
                prize_month_array.push(prize_m);
            }
            
            var prize_s = $('.' + val + '-s').map(function(){
                return $.trim($(this).text())}).get();;

            console.log("setup "+ prize_s);

            if (prize_s != ''){
                console.log(" hay precio setup ");
                prize_setup_array.push(prize_s);
            }
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
            //prize_setup_total = parseFloat(prize_setup_array[i]) + parseFloat(prize_setup_total);
            var prize_num =parseFloat(prize_setup_array[i]);
            prize_setup_total = prize_num + prize_setup_total;
            console.log("precio setup TOTAL " + prize_setup_total);

        }

        var total_m = prize_month_total +month_initial;
        var round_total_m = total_m.toFixed();
        var round_total_s = prize_setup_total.toFixed();
        $('#monthly-total-recal').html(round_total_m + '€/mes');
        $('#setup-total-recal').html(round_total_s + '€');

    });

    $
})

</script>

@stop