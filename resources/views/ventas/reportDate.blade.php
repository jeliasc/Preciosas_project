@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 align="center">Reporte de Ventas</h1>
@stop

@section('content')     
{!! Form::open(['method' => 'post', 'action'=>'App\Http\Controllers\VentasController@reportResult', 'files'=>true]) !!}
    <div class="row">
        <div class="col-12 col-md-3 text-center">
            <span>Fecha Inicial</span>
            <div class="form-group">
                <input class="form-group" type="date" name="fechaInicio" value="{{old('fechaInicio')}}" id="fechaInicio">
            </div>
        </div>
        <div class="col-12 col-md-3 text-center">
            <span>Fecha Final</span>
            <div class="form-group">
                <input class="form-group" type="date" name="fechaFinal" value="{{old('fechaFinal')}}" id="fechaFinal">
            </div>
        </div>   
        <div class="col-12 col-md-3 text-center">
            <div class="form-group">
                <button type="submit" class="btn btn-success">Consultar</button>
            </div>
        </div>   
        <div class="col-12 col-md-3 text-center">
            <span>Total de Ingresos</span>
            <div class="form-group">
                <strong>Q. {{$total}} <br><br></strong>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                <th scope="col">No. Factura</th>
                <th scope="col">Usuario</th>
                <th scope="col">Cliente</th>
                <th scope="col">Fecha</th>
                <th scope="col">Total</th>
                <th scope="col">Impuesto</th>
                <th scope="col">Estado</th>
            </tr>
        </thead>
        <tbody>
            @if($ventas)
                @foreach($ventas as $venta)
                    <tr>
                        <td>{{$venta->numero_factura}}</td>
                        <td>{{$venta->user->name}}</td>
                        <td>{{$venta->cliente->nombre}}</td>
                        <td>{{$venta->fecha}}</td>
                        <td>{{$venta->total}}</td>
                        <td>{{$venta->tax}}</td>
                        <td>{{$venta->estado->nombre}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>       
    </table>
@stop

@section('css')
@stop

@section('js')
<script src="{{ asset('js/ventas/fecha.js') }}"></script>
@stop
        