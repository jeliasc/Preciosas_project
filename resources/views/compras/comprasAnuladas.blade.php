@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Compras Anuladas</h1>
@stop

@section('content')
    @if(Session::has('print'))
        <p class="bg-primary">
            {{session('print')}}
        </p>
    @endif

{!! Form::open(['url' => ' ']) !!}
    <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Usuario</th>
                <th scope="col">Proveedor</th>
                <th scope="col">Fecha</th>
                <th scope="col">Impuesto</th>
                <th scope="col">Total</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($compras)
                @foreach($compras as $compra)
                    <tr>
                        <td>{{$compra->id}}</td>
                        <td>{{$compra->user->name}}</td>
                        <td>{{$compra->proveedor->nombre}}</td>
                        <td data-order="{{ $compra->fecha }}">
                            {{ date("d/m/Y H:i:s", strtotime($compra->fecha)) }}
                        </td>                        
                        <td>{{$compra->tax}}</td>
                        <td>{{$compra->total}}</td>
                        <td>{{$compra->estado->nombre}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                    @can('ver compras')
                                        <a href="{{ route('detalleCompra', ['id'=>$compra->id]) }}"><i class="fa fa-regular fa-eye" title="Detalles de compra" aria-hidden="true"></i></a>
                                    @endcan
                                </div>
                                <div class="col-md-6">
                                    @can('ver compras')
                                        <a href="{{ route('compra.pdf', ['id'=>$compra->id]) }}"><i class="fa fa-regular fa-file-pdf" title="Exportar a PDF" aria-hidden="true"></i></a>
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
@stop

@section('js')
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
@stop
        