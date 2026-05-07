@extends('adminlte::page')

@section('title', 'Dashboard')

@php
    use Carbon\Carbon;
    Carbon::setLocale('es');
@endphp

@section('content_header')
    <h1>Ventas Facturadas</h1>
@stop

@section('content')
    @if(Session::has('error'))
        <p class="bg-danger">
            {{session('error')}}
        </p>
    @endif
    @if(Session::has('print'))
        <p class="bg-primary">
            {{session('print')}}
        </p>
    @endif
{!! Form::open(['url' => ' ']) !!}
    <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th scope="col">No. Factura</th>
                <th scope="col">Usuario</th>
                <th scope="col">Cliente</th>
                <th scope="col">Fecha</th>
                <th scope="col">Total</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @if($ventas)
                @foreach($ventas as $venta)
                    <tr>
                        <td>{{$venta->numero_factura}}</td>
                        <td>{{$venta->user->name}}</td>
                        <td>{{$venta->cliente->nombre}}</td>
                        <td data-order="{{ $venta->fecha }}">
                            {{ Carbon::parse($venta->fecha)->translatedFormat('d \\d\\e F Y H:i') }}
                        </td>
                        <td>{{$venta->total}}</td>
                        <td>{{$venta->estado->nombre}}</td>
                        <td>
                            <div class="row">
                                <div class="col-lg-3">
                                    @can('ver ventas')
                                        <a href="{{ route('detalleVenta', ['id'=>$venta->id]) }}"><i class="fa fa-regular fa-eye" title="Detalles de venta" aria-hidden="true"></i></a>
                                    @endcan
                                </div>
                                <div class="col-lg-3">
                                    @can('ver ventas')
                                        @if($venta->estado_id == 3)
                                            <a class="btn-modal-editar" type="button" onclick="return ventaDelete({{$venta->id}})"><i class="fa-solid fa-ban" aria-hidden="true" title="Anular venta"></i></a>
                                        @endif                                    
                                    @endcan
                                </div>
                                <div class="col-lg-3">
                                    @can('ver ventas')
                                        <a href="{{ route('venta.print', ['id'=>$venta->id]) }}"><i class="fa fa-solid fa-print" aria-hidden="true" title="Imprimir"></i></a>
                                    @endcan
                                </div>
                                <div class="col-lg-3">
                                    @can('ver ventas')
                                        <a href="{{ route('venta.pdf', ['id'=>$venta->id]) }}"><i class="fa fa-regular fa-file-pdf" title="Exportar a PDF" aria-hidden="true"></i></a>
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

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/dataTable/dataTable.js') }}"></script>
    <script src="{{ asset('js/preciosas/ventas/ventaDelete.js') }}"></script>
@stop
        