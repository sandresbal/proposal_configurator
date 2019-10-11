@extends('layouts.app')

@section('content')
<div class="container">
    
    @if (Auth::check())
                        <table class="table">
                            <thead><tr>
                            <td>
                            Nombre
                            </td>
                            <td>
                            Compañía
                            </td>
                            <td>
                            Coste mensual recurrente
                            </td>
                            <td>
                            Coste set-up
                            </td>
                            <td>
                            Acciones
                            </td>
                            </tr>
                        </thead>
                        <tbody>@foreach($proposals as $proposal)
                            <tr>
                                <td>
                                    {{$proposal->name}}
                                </td>
                                <td>
                                    {{$proposal->company}}
                                </td>
                                <td>
                                    {{$proposal->monthly_cost_total}}
                                <td>
                                    {{$proposal->set_up_cost_total}}
                                </td>
                                </td>
                                <td>
                                        <form action="/proposal/{{$proposal->id}}/view">
                                        <button type="submit" name="edit" class="btn btn-primary">View proposal</button>
                                        
                                        {{ csrf_field() }}
                                    </form>
                                    <!--<form action="/proposal/{{$proposal->id}}/edit">
                                        <button type="submit" name="edit" class="btn btn-primary">Edit</button>
                                        
                                        {{ csrf_field() }}
                                    </form>-->

                                    <form action="/proposal/{{$proposal->id}}/delete">

                                        <button type="submit" name="delete" formmethod="POST" class="btn btn-danger">Delete</button>

                                        {{ csrf_field() }}
                                    </form>
                                </td>
                            </tr>
                            @endforeach</tbody>
                        </table>
                @else
                    <h3>You need to log in. <a href="/login">Click here to login</a></h3>
                @endif

</div>
@endsection
