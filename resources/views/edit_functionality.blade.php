@extends('layouts.app')

@section('content')
<div class="container">

        @if (Auth::check())

        <h2>Editar funcionalidad</h2>
               
    <form method="POST" action="/functionality/{{$functionality->id}}">

    <div class="form-group">
        <label for="name">Nombre</label>
        <input name="name" class="form-control" value="{{$functionality->name}}">
        <label for="description_short">Descripcion corta</label>
        <input name="description_short" class="form-control" value="{{$functionality->description_short}}">
        <label for="description_long">Descripcion larga</label>
        <textarea name="description_long" class="form-control">{{$functionality->description_long}}</textarea>
        <label for="price_monthly">Precio mensual:</label>
        <input type="number" name="price_monthly" value="{{$functionality->price_monthly}}" ><br>
        <label for="price_setup">Precio setup:</label>
        <input type="number" name="price_setup" value="{{$functionality->price_setup}}" ><br>
        <!--Selecciona el tipo de coste:
        <select id='payment' name='payment'>
            <option value='Set-up'>Set-up</option>
            <option value='Monthly fee'>Mensual</option>
        </select><br>-->
        <!--Selecciona si admite unidades (como la implementación de idiomas):
        <select id='units' name='units'>
            <option value='no'>No modificar</option>
            <option value='true'>Sí</option>
            <option value='false'>No</option>
        </select>--><br>
        <label for="atom">¿Entra en ATOM?</label>
        <select id='atom' name='atom'>
            <option value='no'>No modificar</option>
            <option value='base'>Sí</option>
            <option value='extra'>Es un extra</option>
        </select><br>
        Selecciona el dispositivo en el que se desarrolla funcionalidad:
        <select id='device' name='device'>
            <option value='no'>No modificar</option>
            <option value='CMS'>CMS</option>
            <option value='Web TV'>Web</option>
            <option value='Aplicaciones móviles'>Aplicación móvil</option>
            <option value='Aplicaciones de TV conectada'>Aplicación de TV</option>
        </select>
        
        <br>
        Modelos de negocio actuales:
            @foreach($functionality->getBizmodels() as $bizmodel)
                {{$bizmodel->name}}
            @endforeach
        <br>
        Selecciona nuevos modelos de negocio si quieres editar:<br>
            <input type="checkbox" name="bizmodels[]" value="1" checked>Advertising VOD<br>
            <input type="checkbox" name="bizmodels[]" value="2" checked>Susbcription VOD<br>
            <input type="checkbox" name="bizmodels[]" value="3" checked>Pay per view VOD<br>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Añadir Funcionalidad</button>
    </div>
{{ csrf_field() }}
</form>

        @else
            <h3>You need to log in. <a href="/login">Click here to login</a></h3>
        @endif

</div>
@endsection