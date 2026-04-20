@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div>
    <label class="form-control-label" for="fecha_compra">Fecha de venta</label>
    <p>{{date("d/m/Y H:i:s", strtotime($venta->fecha))}}</p>
</div>
@stop

@section('content')
<br />
<div class="form-group row">
    <div  class="col-12 col-md-4 text-center">
        <label class="form-control-label" for="nombre">Vendedor</label>
        <p>{{$venta->user->name}}</p>
    </div>
    <div  class="col-12 col-md-3 text-center">
        <label class="form-control-label" for="nombre">Cliente</label>
            <br>
            <p>{{$venta->cliente->nombre}}</p>
    </div>
        <div  class="col-12 col-md-3 text-center">
        <label class="form-control-label" for="nombre">No. Factura</label>
            <br>
            <p>{{$venta->numero_factura}}</p>
    </div>
</div>
<div class="form-group row">
    <h5>Detalles de venta</h5>
    <div class="table-responsive col-md-12 text-center">
        <table id="detalles" class="table">
            <thead>
                <th>Cantidad</th>
                <th>Artículo</th>
                <th>Comentario</th>
                <th>Precio/V (Q)</th>
                <th>Extra (Q)</th>
                <th>descuento (Q)</th>
                <th>SubTotal(Q)</th>
            </thead>
            <tbody>
                @foreach($detalleVentas as $detalleVenta)
                    <tr>
                        <td>{{$detalleVenta->cantidad}}</td>
                        <td>{{$detalleVenta->producto->nombre}}</td>
                        <td>{{$detalleVenta->comentario}}</td>
                        <td>Q. {{$detalleVenta->precio}}</td>
                        <td>Q. {{$detalleVenta->extra}}</td>
                        <td>Q. {{$detalleVenta->descuento}}</td>
                        <td>Q. {{number_format(((($detalleVenta->cantidad*$detalleVenta->precio)+$detalleVenta->extra)-$detalleVenta->descuento),2)}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">
                        <p align="right">TOTAL IMPUESTO (12%)</p>
                    </th>
                    <th>
                        <p align="center">Q. {{number_format(($venta->tax),2)}}</p>
                    </th>
                </tr>
                <tr>
                    <th colspan="6">
                        <p align="right">TOTAL A PAGAR</p>
                    </th>
                    <th>
                        <p align="center">Q. {{number_format(($venta->total),2)}}</p>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>    
</div>
<table id="myTable" class="dt-responsive nowrap" style="width:20%">
    <tr>       
        <td>
            @if($venta->estado_id ==3)
                <a class="btn btn-success btn-block" type="button" role="button">{{$venta->estado->nombre}}</a>  
            @else
                <a class="btn btn-danger btn-block" type="button" role="button">{{$venta->estado->nombre}}</a>      
            @endif
        </td>
        <td>
            @if($venta->estado_id ==3)
                <a class="btn btn-primary btn-block" href="{{ route('ventasIndex') }}" role="button">Regresar</a>
            @else
                <a class="btn btn-primary btn-block" href="{{ route('ventasAnuladas') }}" role="button">Regresar</a>
            @endif
        </td> 
    </tr>
</table>  
 @stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
@stop
