@extends('adminlte::page')

@section('title', 'Index_Clientes')

@section('content_header')
    <h1>Clientes</h1>
@stop

@section('content')
@can('crear clientes')
    <p><a class="btn btn-outline-success" type="button" onclick="return clienteCreate()">Nuevo</a></p>
@endcan

{{-- formulario de index clientes--}}  

{!! Form::open(['url' => '']) !!}
    <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nit</th>
                <th scope="col">Nombre</th>
                <th scope="col">Dirección</th>
                <th scope="col">Teléfono</th>
                <th scope="col">E-mail</th>
                <th scope="col">Estado</th>
                <th scope="col">Creado</th>
                <th scope="col">Actualizado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($clientes)
                @foreach($clientes as $cliente)
                    <tr>
                        <td>{{$cliente->id}}</td>
                        <td>{{$cliente->nit}}</td>
                        <td>{{$cliente->nombre}}</td>
                        <td>{{$cliente->direccion}}</td>
                        <td>{{$cliente->telefono}}</td>
                        <td>{{$cliente->email}}</td>
                        <td>{{$cliente->estado->nombre}}</td>
                        <td>{{$cliente->created_at}}</td>
                        <td>{{$cliente->updated_at}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                    @can('editar clientes')
                                        <a  class="btn-modal-editar" type="button" onclick="return clienteEdit({{$cliente->id}})"> <i class="fa fa-pencil-square-o" title="Editar clientes" aria-hidden="true"></i></a>
                                    @endcan
                                </div>
                                <div class="col-md-6">
                                    @can('eliminar clientes')
                                            @if ($cliente->estado_id == 2)
                                                <a  class="btn-modal-editar" type="button" onclick="return clienteDelete({{$cliente->id}})"><i class="fa fa-check-square-o" aria-hidden="true" title="Habilitar clientes"></i></a>
                                            @else
                                                <a  class="btn-modal-editar" type="button" onclick="return clienteDelete({{$cliente->id}})"><i class="fa-solid fa-ban" aria-hidden="true" title="Deshabilitar clientes"></i></a>    
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

{{-- Fin de formulario index clientes --}}

@include('clientes.create')
@include('clientes.edit')

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
    <script src="{{ asset('js/preciosas/clientes/clienteCreate.js') }}"></script>
    <script src="{{ asset('js/preciosas/clientes/clienteUpdate.js') }}"></script>
    <script src="{{ asset('js/preciosas/clientes/clienteDelete.js') }}"></script> 
@stop