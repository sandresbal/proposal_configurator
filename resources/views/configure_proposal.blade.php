@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nueva propuesta</h1>

    <form action="/proposal/draft" method="post">
    @csrf
        <div class="form-group">
        <label for="company">Compañía:</label>
        <input name="company" class="form-control" placeholder="Compañía" >
        <label for="creator">Nombre del autor:</label>
        <input name="creator" class="form-control" placeholder="Autor" >
        <label for="description">Descripción de la propuesta:</label>
        <input name="description" class="form-control" placeholder="Descripción" >
        <label for="receiver">Nombre del destinatario:</label>
        <input name="receiver" class="form-control" placeholder="Destinatario" >
        <label for="bizmodel">Modelo de negocio:</label>
        <select id='bizmodel' name='bizmodel'>
            @foreach($bizmodels as $bizmodel)
            <option value='{{ $bizmodel->id }}'>{{$bizmodel->name}}</option>
            @endforeach
        </select><br>
        <label for="cdn">CDN:</label>
        <select id='cdn' name='cdn'>
            @foreach($cdns as $cdn)
            <option value='{{ $cdn->id }}'>{{$cdn->name}} con {{$cdn->concurrency}} concurrentes</option>
            @endforeach
        </select><br>
        <label for="live">¿Incluimos herramienta de directos en el CMS?</label>
        <select id='live' name='live'>
            <option value='no'> No</option>
            @foreach($liveplans as $live)
            <option value='{{ $live->id }}'>Sí, plan {{$live->name}} con {{$live->concurrency}} concurrentes e ingesta de {{$live->channels}} canales </option>
            @endforeach
        </select><br>

        <!--<label for="multilang">¿Va a querer más de un idioma en la interfaz?</label>
            <input id="multilang" type="checkbox" value="multilanginterface" name="multilanginterface"/>Sí
            <div id="multilang_div" style="display:none">¿Cuántos?
            <input id="multilang_input" type="number" name="languages" class="form-control" >
            </div>
        <br>

        <label for="multicurrency">¿Va a querer más de una divisa en los planes de pago de la web?</label>
            <input id="multicurrency" type="checkbox" value="multicurrency" name="multicurrency"/>Sí
            <div id="multicurrency_div" style="display:none">¿Cuántas?
            <input id="multicurrency_input" type="number" name="languages" class="form-control" >
            </div>
        <br>-->

        <label for="soporte24">¿Incluimos soporte 24x7 en la propuesta?</label>
        <select id='soporte24' name='soporte24'>
            <option value='0'> No</option>
            <option value='1'> Sí</option>
        </select><br>
        
        <label for="devices">Selecciona lo que quieras incluir en la propuesta. Ten en cuenta que Amazon Fire TV Stick y Huawei son extensiones de Android TV y de Android móvil, respectivamente, así que si quieres incluir las primeras, incluye las segundas:</label><br>
            @foreach ($devices as $device)
            <input id="devices" type="checkbox" value="{{$device->id}}" name="devices[]" checked/>
            <label for="{{$device->id}}">{{$device->name}} {{$device->os}}</label><br>
            @endforeach
        <label for="package">Paquete comercial:</label>
        <select id='package' name='package'>
            <option value='base'>Puro ATOM</option>
            <option value='extra'>Base ATOM + Funcionalidades extras</option>
            <option value='customizated'>Base ATOM + Customizaciones (hay que crearlas como funcionalidades custom)</option>
        </select><br>
        </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" id="draft" value="{{ csrf_token() }}">Ver borrador de la propuesta</button>
    </div>
    </form> 

</div>

<script type="text/javascript">

$(document).ready(function () {

    $('#multilang').change(function () {
        $('#multilang_div').toggle();
    });
    $('#multicurrency').change(function () {
        $('#multicurrency_div').toggle();
    });
})
</script>
@stop