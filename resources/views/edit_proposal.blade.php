@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nueva propuesta</h1>

    <form action="/edit_details/{{$proposal->id}}" method="post">
    @csrf
        <div class="form-group">
        <label for="company">Compañía:</label>
        <input name="company" class="form-control" value="{{$proposal->company}}">
        <label for="creator">Nombre del autor:</label>
        <input name="creator" class="form-control" value="{{$proposal->creator}}">
        <label for="name">Nombre de la propuesta:</label>
        <input name="name" class="form-control" value="{{$proposal->name}}">
        <label for="description">Descripción de la propuesta:</label>
        <input name="description" class="form-control" value="{{$proposal->name}}" >
        <label for="receiver">Nombre del destinatario:</label>
        <input name="receiver" class="form-control" value="{{$proposal->receiver}}" >
        <label for="bizmodel">Modelo de negocio (escogido previamente {{$bizmodel->name}}):</label>
        <select id='bizmodel' name='bizmodel'>
            @foreach($bizmodels as $bizmodel)
            <option value='no'>No modificar</option>
            <option value='{{ $bizmodel->id }}'>{{$bizmodel->name}}</option>
            @endforeach
        </select><br>
        <label for="bizmodel">CDN (escogido previamente {{$cdn_name}}):</label>
        <select id='cdn' name='cdn'>
            <option value='no'>No modificar</option>
            @foreach($cdns as $cdn)
            <option value='{{ $cdn->id }}'>{{$cdn->name}} con {{$cdn->concurrency}} concurrentes</option>
            @endforeach
        </select><br>
        <label for="CMS">Incluimos CMS:</label>
        <select id='CMS' name='CMS'>
            <option value='no'>No modificar</option>
            <option value='fractal'>Sí, CMS Fractal </option>
            <option value='none'>No, el cliente dispone de otro CMS o no lo necesita</option>
        </select><br>
        <label for="devices">Dispositivos</label> (escogidos previamente 
            @foreach ($proposal_devices as $prop_dev)
            {{$prop_dev->name}} {{$prop_dev->os}}, 
            @endforeach
            ):<br>
            @foreach ($devs as $device)
                <input id="devices" type="checkbox" value="{{$device->id}}" name="devices[]" 
                checked/>
                <label for="{{$device->id}}">{{$device->name}} {{$device->os}}</label><br>
            @endforeach
        <label for="live">¿Incluimos herramienta de directos en el CMS? (Escogido previamente: {{$live_name}})</label>
        <select id='live' name='live'>
            <option value='same'>No modificar</option>
            <option value='no'>No</option>
            @foreach($liveplans as $live)
            <option value='{{ $live->id }}'>Sí, plan {{$live->name}} con {{$live->concurrency}} concurrentes e ingesta de {{$live->channels}} canales </option>
            @endforeach
        </select><br>
        <label for="package">Paquete comercial (escogido previamente {{$pack_name}}):</label>
        <select id='package' name='package'>
            <option value='no'>No modificar</option>
            <option value='base'>Puro ATOM</option>
            <option value='extra'>Base ATOM + Funcionalidades adicionales</option>
            <option value='customizated'>Base ATOM + Funcionalidades custom (hay que crearlas como extras)</option>
        </select><br>
        </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary" id="draft" value="{{ csrf_token() }}">Ver borrador de la propuesta</button>
    </div>
    </form> 

</div>

@stop