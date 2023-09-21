@extends('adminlte::page')

@section('title', 'Index_Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')
@include('users.edit')

    {{-- formulario de index usuarios--}}   
    
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
                                    <a  class="btn-modal-editar" type="button"  onclick="return userEdit({{$user->id}})"> <i class="fa fa-pencil-square-o" title="Editar Usuario" aria-hidden="true"></i></a>
                                    </div>
                                    <div class="col-md-6">
                                        @can('eliminar usuarios')
                                            <a href="#">
                                                @if ($user->estado_id == 2)
                                                    <i class="fa fa-check-square-o" aria-hidden="true" title="Habilitar Usuario"></i>
                                                @else
                                                    <i class="fa fa-ban" aria-hidden="true" title="Deshabilitar Usuario"></i>
                                                @endif
                                            </a>
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

@stop

@section('css')
@stop

@section('js')
    <script> $('#UserTable').DataTable(); </script>
    <script src="{{ asset('js/userUpdate.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
@stop



