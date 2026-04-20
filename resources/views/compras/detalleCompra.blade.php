@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
@if(Session::has('error'))
    <p class="bg-danger">
        {{session('error')}}
    </p>
@endif
<div>
    <label class="form-control-label" for="fecha_compra">Fecha de compra </label>
    <p>{{date("d/m/Y H:i:s", strtotime($compra->fecha))}}</p>
</div>
@stop

@section('content')
<br />
<div class="form-group row">
    <div class="col-md-6 text-center">
        <label class="form-control-label" for="nombre">Proveedor</label>
        <p>{{$compra->proveedor->nombre}}</p>
    </div>
    <div class="col-md-6 text-center">
        <label class="form-control-label" for="numero_compra">No. Factura </label>
        <p>{{$compra->no_factura}}</p>
    </div>
</div>
<div class="form-group row">
    <h4 align="center">Detalles de compra</h4>
    <div class="table-responsive col-md-12 text-center">
        <table id="detalles" class="table">
            <thead>
                <th>Cantidad</th>
                <th>Artículo</th>
                <th>Precio (Q)</th>
                <th>SubTotal (Q)</th>
            </thead>
            <tbody>
                @foreach($detalleCompras as $detalleCompra)
                    <tr>
                        <td>{{$detalleCompra->cantidad}}</td>
                        <td>{{$detalleCompra->producto->nombre}}</td>
                        <td>Q. {{$detalleCompra->precio}}</td>
                        <td>Q. {{number_format(($detalleCompra->cantidad*$detalleCompra->precio),2)}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">
                        <p align="right">TOTAL IMPUESTO (12%)</p>
                    </th>
                    <th>
                        <p align="center">Q. {{number_format($compra->tax,2)}}</p>
                    </th>
                </tr>
                <tr>
                    <th colspan="3">
                        <p align="right">TOTAL A PAGAR</p>
                    </th>
                    <th>
                        <p align="center">Q. {{number_format($compra->total,2)}}</p>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>    
</div>
<table id="myTable" class="dt-responsive nowrap" style="width:20%">
    <tr>       
        <td>
            @if($compra->estado_id ==3)
                <a class="btn btn-success btn-block" type="button" role="button">{{$compra->estado->nombre}}</a>  
            @else
                <a class="btn btn-danger btn-block" type="button" role="button">{{$compra->estado->nombre}}</a>      
            @endif
        </td> 
        <td>
            @if($compra->estado_id ==3)
                <a class="btn btn-primary btn-block" href="{{ route('comprasIndex') }}" role="button">Regresar</a>
            @else
                <a class="btn btn-primary btn-block" href="{{ route('comprasAnuladas') }}" role="button">Regresar</a>
            @endif
        </td> 
    </tr>
</table>  
 @stop

@section('css')
@stop

@section('js')
@stop
