@extends('layouts.app')

@section('content')
<div class="container">
    

    @if (Auth::check())
                <a href="/functionality" class="btn btn-primary">Añadir nueva funcionalidad</a>
                        <table class="table">
                            <thead><tr>
                            <td>
                            Name
                            </td>
                            <!--<td>
                            Description
                            </td>-->
                            <td>
                            Precio mensual
                            </td>
                            <td>
                            Precio set-up
                            </td>
                            <td>
                            Bizmodels
                            </td>
                            <td>
                            Device
                            </td>
                            <td>
                            Options
                            </td>
                            </tr>
                        </thead>
                        <tbody>@foreach($functionalities as $functionality)
                            <tr>
                                <td>
                                    {{$functionality->name}}
                                </td>
                                <!--<td>
                                    {{$functionality->description_short}}
                                </td>-->
                                <td>
                                    {{$functionality->price_monthly}}
                                </td>
                                <td>
                                    {{$functionality->price_setup}}
                                </td>
                                <td>
                                @foreach($functionality->getBizmodels() as $bizmodel)
                                    {{$bizmodel->name}}
                                @endforeach
                                <td>
                                    {{$functionality->device}}
                                </td>
                                </td>
                                <td>
                                    <form action="/functionality/{{$functionality->id}}">
                                        <button type="submit" name="edit" class="btn btn-primary">Edit</button>

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
