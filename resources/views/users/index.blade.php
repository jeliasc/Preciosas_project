@extends('adminlte::page')

@section('title', 'Index_Usuarios')

@section('content_header')
    <h1>Usuarios</h1>
@stop

@section('content')

    <!-- Modal nuevo clientre -->
    @can('crear usuarios')
        <p><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal" data-bs-whatever="@mdo">Nuevo</button></p>
    @endcan
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Nuevo usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'action'=>'App\Http\Controllers\UsersController@store']) !!}
                    <table class="table table-striped ">
                        <tbody>
                            <tr>
                                <td><label>Nombre</label></td>
                                <td><input class="form-control" type="text" id="name" name="name" placeholder="Clic aquí e ingrese el nombre" required></td>
                            </tr>
                            <tr>
                                <td><label>Role</label></td>
                                <td>
                                    <select name="role_id" id="role_id" class="form-select" required>
                                        <option value="">Clic aquí y seleccione un role</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select> 
                                </td>
                            </tr>
                            <tr>
                                <td><label>E-mail</label></td>
                                <td><input type="text" name="email" id="email" class="form-control" placeholder="Clic aquí e ingrese el e-mail" required></td>
                            </tr> 
                            <tr>
                                <td><label>Password:</label></td>
                                <td><input type="text" name="password" id="password" class="form-control" placeholder="Clic aquí e ingrese la contraseña" required></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">                    
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit" name="enviar">Enviar</button>
                </div>
                </div>
            </div>
            </div>
        {!! Form::close() !!}

    <!-- Fin de modal nuevo clientre -->

    {{-- Inicia formulario de index --}}
    
    @if(Session::has('usuarioCreado'))
        <p class="bg-primary">
            {{session('usuarioCreado')}}
        </p>
    @endif
    @if(Session::has('usuarioActualizado'))
        <p class="bg-primary">
            {{session('usuarioActualizado')}}
        </p>
    @endif
 
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
                                    <div class="col-lg-6">
                                        @can('editar usuarios')

                                         <!-- Modal editar clientre -->   

                                        <a href="" id="btn-modal-editar" type="button" data-bs-toggle="modal" data-bs-target="#updateModal{{$user->id}}" data-bs-whatever="@mdo"><i class="fa fa-pencil-square-o" title="Editar Usuario" aria-hidden="true"></i></a>
                                        @endcan
                                        <div class="modal fade" id="updateModal{{$user->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {!! Form::model($user, ['method' => 'post', 'action'=>['App\Http\Controllers\UsersController@update', $user->id]]) !!}
                                                    <table class="table table-striped dt-responsive nowrap" style="width:100%">
                                                        <tr>
                                                            <td>{!! Form::label('name', 'Nombre') !!}</td>
                                                            <td>{!! Form::text('name') !!}
                                                                @error('name')
                                                                <small> 
                                                                    <strong>{{$message}}</strong>
                                                                </small>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>{!! Form::label('email', ' E-mail') !!}</td>
                                                            <td>{!! Form::text('email') !!}
                                                                @error('email')
                                                                <small> 
                                                                    <strong>{{$message}}</strong>
                                                                </small>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><label>Role</label></td>
                                                            <td>
                                                                <select name="role_id" id="role_id" class="form-select" required>
                                                                    @foreach($roles as $role)
                                                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                                                    @endforeach
                                                                </select> 
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    </div>
                                                    <div class="modal-footer">                    
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                                        <button class="btn btn-primary" type="submit" name="enviar">Actualizar</button>
                                                    </div>
                                                    </div>
                                                </div>
                                                </div>
                                            {!! Form::close() !!}
                                            
                                        <!-- Fin de modal editar clientre -->
                                    </div>
                                    <div class="col-lg-6">
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

    {{-- Fin de formulario index --}}
 
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script> $('#myTable').DataTable(); </script>
    <script src="{{ asset('js/userUpdate.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
@stop



