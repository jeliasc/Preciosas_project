@extends('adminlte::page')

@section('title', 'Index_Productos')

@section('content_header')
    <h1>Artículos</h1>
@stop

@section('content')
@can('crear articulos')
    <p><a class="btn btn-outline-success" type="button" onclick="return productoCreate()">Nuevo</a></p>
@endcan

{{-- formulario de index productos--}}  

{!! Form::open(['url' => '']) !!}
    <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Imagen</th>
                <th scope="col">Código</th>
                <th scope="col">Nombre</th>
                <th scope="col">Stock</th>
                <th scope="col">Precio/Venta</th>
                <th scope="col">Descripción</th>
                <th scope="col">Categoría</th>
                <th scope="col">Proveedor</th>
                <th scope="col">Estado</th>
                <th scope="col">Creado</th>
                <th scope="col">Actualizado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($productos)
                @foreach($productos as $producto)
                    <tr>
                        <td>{{$producto->id}}</td>
                            @if($producto->foto)
                                <td><img src="images/{{$producto->foto->ruta}}" style="height: 50px"/></td>
                            @else
                        <td><img src="/images/no_img.png" style="height: 50px;" /></td>
                            @endif
                        <td>{{$producto->code}}</td>
                        <td>{{$producto->nombre}}</td>
                        <td>{{$producto->stock}}</td>
                        <td>{{$producto->precio}}</td>
                        <td>{{$producto->descripcion}}</td>
                        <td>{{$producto->categoria->nombre}}</td>
                        <td>{{$producto->proveedor->nombre}}</td>
                        <td>{{$producto->estado->nombre}}</td>
                        <td>{{$producto->created_at}}</td>
                        <td>{{$producto->updated_at}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                    @can('editar articulos')
                                        <a  class="btn-modal-editar" type="button" onclick="return productoEdit({{$producto->id}})"> <i class="fa fa-pencil-square-o" title="Editar productos" aria-hidden="true"></i></a>
                                    @endcan
                                </div>
                                <div class="col-md-6">
                                    @can('eliminar articulos')
                                            @if ($producto->estado_id == 2)
                                                <a  class="btn-modal-editar" type="button" onclick="return productoDelete({{$producto->id}})"><i class="fa fa-check-square-o" aria-hidden="true" title="Habilitar productos"></i></a>
                                            @else
                                                <a  class="btn-modal-editar" type="button" onclick="return productoDelete({{$producto->id}})"><i class="fa-solid fa-ban" aria-hidden="true" title="Deshabilitar productos"></i></a>    
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

{{-- Fin de formulario index productos --}}

@include('productos.create')
@include('productos.edit')

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
    <script src="{{ asset('js/preciosas/productos/productoCreate.js') }}"></script>
    <script src="{{ asset('js/preciosas/productos/productoUpdate.js') }}"></script>
    <script src="{{ asset('js/preciosas/productos/productoDelete.js') }}"></script>
@stop