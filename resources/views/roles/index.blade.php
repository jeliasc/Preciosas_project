@extends('adminlte::page')

@section('title', 'Index_Roles')

@section('content_header')
    <h1>Roles</h1>
@stop

@section('content')
@can('crear roles')
    <p><a class="btn btn-outline-success" type="button" onclick="return roleCreate()">Nuevo</a></p>
@endcan

{{-- formulario de index roles--}}  

{!! Form::open(['url' => '']) !!}
    <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Estado</th>
                <th scope="col">Creado</th>
                <th scope="col">Actualizado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($roles)
                @foreach($roles as $role)
                    <tr>
                        <td>{{$role->id}}</td>
                        <td>{{$role->name}}</td>
                        <td>{{$role->estado->nombre}}</td>
                        <td>{{$role->created_at}}</td>
                        <td>{{$role->updated_at}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                    @can('editar roles')
                                        <a  class="btn-modal-editar" type="button" onclick="return roleEdit({{$role->id}})"> <i class="fa fa-pencil-square-o" title="Editar Role" aria-hidden="true"></i></a>
                                    @endcan
                                </div>
                                <div class="col-md-6">
                                    @can('eliminar roles')
                                            @if ($role->estado_id == 2)
                                                <a  class="btn-modal-editar" type="button" onclick="return roleDelete({{$role->id}})"><i class="fa fa-check-square-o" aria-hidden="true" title="Habilitar Role"></i></a>
                                            @else
                                                <a  class="btn-modal-editar" type="button" onclick="return roleDelete({{$role->id}})"><i class="fa-solid fa-ban" aria-hidden="true" title="Deshabilitar Role"></i></a>    
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

{{-- Fin de formulario index roles --}}

@include('roles.create')
@include('roles.edit')

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
    <script src="{{ asset('js/preciosas/roles/roleCreate.js') }}"></script>
    <script src="{{ asset('js/preciosas/roles/roleUpdate.js') }}"></script>
    <script src="{{ asset('js/preciosas/roles/roleDelete.js') }}"></script>
@stop