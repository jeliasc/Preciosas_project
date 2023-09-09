@extends('adminlte::page')

@section('title', 'Crear_Usuario')

@section('content_header')
    <h1>Crear Usuario</h1>
@stop

@section('content')
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
                <select name="role_id" id="role_id" class="form-select">
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
        <tr>
            <td colspan="2">
                <button class="btn btn-success" type="submit" name="enviar">Enviar</button>
                <button class="btn btn-danger" type="reset" name="cancelar">Cancelar</button>
            </td>
        </tr>
    </tbody>
  </table>
{!! Form::close() !!}
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
