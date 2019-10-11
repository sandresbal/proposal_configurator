@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Configurador de propuestas</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    Bienvenido al configurador de propuestas comerciales. <br>
                    <ul>
                    <li><a href="/proposal_new">Crea una nueva propuesta</a>.Ten en cuenta que si es una propuesta con customizaciones, antes deberás crearlas en <a href="/list_functionalities">Gestionar funcionalidades o customizaciones</a></li>
                    <li><a href="/list_proposals">Ver, editar o borrar las propuestas guardadas</a><br></li>
                    <li><a href="/list_functionalities">Gestionar funcionalidades o customizaciones</a><br></li>
                    </ul>
                    <!--<a href="/proposal_new">Crea una nueva propuesta</a>.Ten en cuenta que si es una propuesta con customizaciones, antes deberás crearlas en <a href="/list_functionalities">Gestionar funcionalidades o customizaciones</a>)
                    <a href="/list_proposals">Ver, editar o borrar las propuestas guardadas</a><br>
                    <a href="/list_functionalities">Gestionar funcionalidades o customizaciones</a><br>-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
