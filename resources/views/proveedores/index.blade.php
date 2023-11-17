@extends('adminlte::page')

@section('title', 'Index_Proveedores')

@section('content_header')
    <h1>Proveedores</h1>
@stop

@section('content')
@can('crear proveedores')
    <p><a class="btn btn-outline-success" type="button" onclick="return proveedorCreate()">Nuevo</a></p>
@endcan

{{-- formulario de index proveedores--}}  

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
            @if($proveedores)
                @foreach($proveedores as $proveedor)
                    <tr>
                        <td>{{$proveedor->id}}</td>
                        <td>{{$proveedor->nit}}</td>
                        <td>{{$proveedor->nombre}}</td>
                        <td>{{$proveedor->direccion}}</td>
                        <td>{{$proveedor->telefono}}</td>
                        <td>{{$proveedor->email}}</td>
                        <td>{{$proveedor->estado->nombre}}</td>
                        <td>{{$proveedor->created_at}}</td>
                        <td>{{$proveedor->updated_at}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                    @can('editar proveedores')
                                        <a  class="btn-modal-editar" type="button" onclick="return proveedorEdit({{$proveedor->id}})"> <i class="fa fa-pencil-square-o" title="Editar proveedores" aria-hidden="true"></i></a>
                                    @endcan
                                </div>
                                <div class="col-md-6">
                                    @can('eliminar categorias')
                                            @if ($proveedor->estado_id == 2)
                                                <a  class="btn-modal-editar" type="button" onclick="return proveedorDelete({{$proveedor->id}})"><i class="fa fa-check-square-o" aria-hidden="true" title="Habilitar proveedores"></i></a>
                                            @else
                                                <a  class="btn-modal-editar" type="button" onclick="return proveedorDelete({{$proveedor->id}})"><i class="fa-solid fa-ban" aria-hidden="true" title="Deshabilitar proveedores"></i></a>    
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

{{-- Fin de formulario index proveedores --}}

@include('proveedores.create')
@include('proveedores.edit')

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
    <script src="{{ asset('js/preciosas/proveedores/proveedorCreate.js') }}"></script>
    <script src="{{ asset('js/preciosas/proveedores/proveedorUpdate.js') }}"></script>
    <script src="{{ asset('js/preciosas/proveedores/proveedorDelete.js') }}"></script>
@stop