@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Ventas Anuladas</h1>
@stop

@section('content')
    @if(Session::has('print'))
        <p class="bg-primary">
            {{session('print')}}
        </p>
    @endif
    @if(Session::has('eAut'))
        <p class="bg-warning">
            {{session('eAut')}}
        </p>
    @endif
    @if(Session::has('ventaCreada'))
        <p class="bg-primary">
            {{session('ventaCreada')}}
        </p>
    @endif
    @if(Session::has('ventaEliminada'))
        <p class="bg-warning">
            {{session('ventaEliminada')}}
        </p>
    @endif
    @if(Session::has('deshabilitado'))
        <p class="bg-warning">
            {{session('deshabilitado')}}
        </p>
    @endif
     @if(Session::has('habilitado'))
         <p class="bg-primary">
             {{session('habilitado')}}
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
                        <td><a href="{{ route('detalleVenta', ['id'=>$venta->id]) }}">{{$venta->numero_factura}}</a></td>
                        <td>{{$venta->user->name}}</td>
                        <td>{{$venta->cliente->nombre}}</td>
                        <td>{{$venta->fecha}}</td>
                        <td>{{$venta->total}}</td>
                        <td>{{$venta->estado->nombre}}</td>
                        <td>
                            <div class="row">
                                <div class="col-lg-4">
                                    @can('ver ventas')
                                        <a href="{{ route('detalleVenta', ['id'=>$venta->id]) }}"><i class="fa fa-regular fa-eye" title="Detalles de compra" aria-hidden="true"></i></a>
                                    @endcan
                                </div>
                                <div class="col-lg-4">
                                    @can('ver ventas')
                                        <a href="{{ route('venta.print', ['id'=>$venta->id]) }}"><i class="fa fa-solid fa-print" aria-hidden="true" title="Imprimir"></i></a>
                                    @endcan
                                </div>
                                <div class="col-lg-4">
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
    <script> $('#myTable').DataTable(); </script>
@stop
        