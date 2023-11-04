@extends('adminlte::page')

@section('title', 'Index_Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop
@section('content')

    {{-- formulario de index usuarios--}}   

        <p><a class="btn btn-outline-success" type="button" onclick="return userCreate()">Nuevo</a></p>

    {!! Form::open(['url' => 'App\Http\Controllers\UsersController@index']) !!}
        <table id="UserTable" class="table table-striped dt-responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Role</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Creado</th>
                    <th scope="col">Actualizado</th>
                    <th scope="col">Acciones</th>
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
                            <td>
                                <div class="row">
                                    <div class="col-md-6">
                                    <a  class="btn-modal-editar" type="button" onclick="return userEdit({{$user->id}})"> <i class="fa fa-pencil-square-o" title="Editar Usuario" aria-hidden="true"></i></a>
                                    </div>
                                    <div class="col-md-6">
                                        @can('eliminar usuarios')
                                                @if ($user->estado_id == 2)
                                                    <a  class="btn-modal-editar" type="button" onclick="return userDelete({{$user->id}})"><i class="fa fa-check-square-o" aria-hidden="true" title="Habilitar Usuario"></i></a>
                                                @else
                                                    <a  class="btn-modal-editar" type="button" onclick="return userDelete({{$user->id}})"><i class="fa-solid fa-ban" aria-hidden="true" title="Deshabilitar Usuario"></i></a>    
                                                @endif
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>    
        </table>
    {!! Form::close() !!}
 
    {{-- Fin de formulario index usuarios --}}

@include('users.edit')
@include('users.create')

@stop

@section('css')

@stop

@section('js')
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
    <script src="{{ asset('js/userCreate.js') }}"></script>
    <script src="{{ asset('js/userUpdate.js') }}"></script>
    <script src="{{ asset('js/userDelete.js') }}"></script>
@stop



