@extends('adminlte::page')

@section('title', 'Index_Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')

    <!-- Modal nuevo clientre -->

    <p><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Nuevo</button></p>
    {!! Form::open() !!}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                    <input type="text" class="form-control" id="recipient-name">
                </div>
                <div class="mb-3">
                    <label for="message-text" class="col-form-label">Message:</label>
                    <textarea class="form-control" id="message-text"></textarea>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Enviar</button>
            </div>
            </div>
        </div>
        </div>
    {!! Form::close() !!}

    <!-- Fin de modal nuevo clientre -->

    {{-- Inicia formulario de index --}}
 
    {!! Form::open(['url' => 'App\Http\Controllers\UsersController@index']) !!}
        <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Role</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Creado</th>
                    <th scope="col">Actualizado</th>
                </tr>
            </thead>
            <tbody>
                @if($users)
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>@if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $roleName)
                                        <h6>{{$roleName}}</h6>
                                    @endforeach
                                @endif
                            </td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->estado->nombre}}</td>
                            <td>{{$user->created_at}}</td>
                            <td>{{$user->updated_at}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>    
        </table>
    {!! Form::close() !!}

    {{-- Fin de formulario index --}}

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script> $('#myTable').DataTable(); </script>

@stop



