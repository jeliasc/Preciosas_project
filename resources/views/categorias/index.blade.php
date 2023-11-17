@extends('adminlte::page')

@section('title', 'Index_Categorias')

@section('content_header')
    <h1>Categorías</h1>
@stop

@section('content')
@can('crear categorias')
    <p><a class="btn btn-outline-success" type="button" onclick="return categoriaCreate()">Nuevo</a></p>
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
            @if($categorias)
                @foreach($categorias as $categoria)
                    <tr>
                        <td>{{$categoria->id}}</td>
                        <td>{{$categoria->nombre}}</td>
                        <td>{{$categoria->estado->nombre}}</td>
                        <td>{{$categoria->created_at}}</td>
                        <td>{{$categoria->updated_at}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                    @can('editar categorias')
                                        <a  class="btn-modal-editar" type="button" onclick="return categoriaEdit({{$categoria->id}})"> <i class="fa fa-pencil-square-o" title="Editar Categorias" aria-hidden="true"></i></a>
                                    @endcan
                                </div>
                                <div class="col-md-6">
                                    @can('eliminar categorias')
                                            @if ($categoria->estado_id == 2)
                                                <a  class="btn-modal-editar" type="button" onclick="return categoriaDelete({{$categoria->id}})"><i class="fa fa-check-square-o" aria-hidden="true" title="Habilitar Categorias"></i></a>
                                            @else
                                                <a  class="btn-modal-editar" type="button" onclick="return categoriaDelete({{$categoria->id}})"><i class="fa-solid fa-ban" aria-hidden="true" title="Deshabilitar Categorias"></i></a>    
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

@include('categorias.create')
@include('categorias.edit')

@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
    <script src="{{ asset('js/preciosas/categorias/categoriaCreate.js') }}"></script>
    <script src="{{ asset('js/preciosas/categorias/categoriaUpdate.js') }}"></script>
    <script src="{{ asset('js/preciosas/categorias/categoriaDelete.js') }}"></script>
@stop