@extends('layouts.app')

@section('content')
<div class="container">

        @if (Auth::check())

        <h2>Añadir nueva funcionalidad</h2>
               
    <form method="POST" action="/functionality">

    <div class="form-group">
        <label for="name">Nombre</label>
        <input name="name" class="form-control" placeholder="Nombre de la funcionalidad">
        <label for="description_short">Descripcion corta</label>
        <input name="description_short" class="form-control" placeholder="Descripción corta">
        <label for="description_long">Descripcion larga</label>
        <input name="description_long" class="form-control" placeholder="Descripción larga">
        <label for="prize_monthly">Precio mensual</label>
        <input type="number" name="prize_monthly" class="form-control" >
        <label for="prize_setup">Precio set-up</label>
        <input type="number" name="prize_setup" class="form-control" >
        <br>
        <label for="atom">¿Está incluido en ATOM, es un extra o es custom?</label>
        <select id='atom' name='atom'>
            <option value='base'>Sí</option>
            <option value='extra'>Es un extra</option>
            <option value='customizated'>Es una customización para un proyecto concreto</option>
        </select><br>
        <!--<label for="custom">¿Es una customización para un proyecto concreto?</label>
        <input id="custom" type="checkbox" name="custom"/>Sí
        <br>-->
        Selecciona el dispositivo en el que se desarrolla funcionalidad:
        <select id='device' name='device'>
            <option value='CMS'>CMS</option>
            <option value='Web TV'>Web</option>
            <option value='Aplicaciones móviles'>Aplicación móvil</option>
            <option value='Aplicaciones de TV conectada'>Aplicación de TV</option>
        </select><br>

        Selecciona modelo de negocio:<br>
            <input type="checkbox" name="bizmodel[]" value="1">Advertising VOD<br>
            <input type="checkbox" name="bizmodel[]" value="2">Susbcription VOD<br>
            <input type="checkbox" name="bizmodel[]" value="3">Pay per view VOD<br>
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