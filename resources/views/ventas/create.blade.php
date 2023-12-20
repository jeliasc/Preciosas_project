@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Crear Venta</h1>
@stop
       
@section('content')
<p>Ingresar los datos requeridos.</p>
    <form id="createForm">
        @CSRF
       <table id="table" class="table table-striped dt-responsive nowrap">      
            <tr>
                <td>{!! Form::label('cliente_id', 'Cliente') !!}</td>
                <td> 
                    <select name="cliente_id" id="cliente_id">
                        <option value="">Seleccione un cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre}}</option>
                        @endforeach
                    </select>  
                </td>
                <td>{!! Form::label('producto_id', 'Artículo') !!}</td>
                <td> 
                    <select name="producto_id" id="producto_id">
                    <option value="">Seleccione un artículo</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}_{{ $producto->stock }}_{{ $producto->precio }}">{{ $producto->nombre}}</option>
                        @endforeach
                    </select>  
                </td>
            </tr>                   
            <tr> 
                <td>{!! Form::label('cantidad', 'Cantidad') !!}</td>
                <td><input type="number" class="form-control" name="cantidad" id="cantidad" aria-describedby="helpId"></td>
                <td>{!! Form::label('stock', 'Stock') !!}</td>
                <td><input type="number" class="form-control" name="stock" id="stock" aria-describedby="helpId" disabled></td>
            </tr>
            <tr> 
                <td>{!! Form::label('precio', 'Precio/V') !!}</td>
                <td><input type="number" class="form-control" name="precio" id="precio" aria-describedby="helpId" disabled></td>
                <td>{!! Form::label('tax', 'Impuesto:') !!}</td>
                <td><input type="number" class="form-control" name="tax" id="tax" value="" placeholder="12%" disabled></td>
            </tr>
            <tr>
                <td>{!! Form::label('extra', 'Extra (Q.)') !!}</td>
                <td><input type="number" class="form-control" name="extra" id="extra" placeholder="Q" value="0"></td>
                <td>{!! Form::label('descuento', 'Descuento (%)') !!}</td>
                <td><input type="number" class="form-control" name="descuento" id="descuento" placeholder="%" value="0"></td>
            </tr>
            <tr>
                <td>{!! Form::label('comentario', 'Comentario') !!}</td>
                <td colspan="5"><input class="form-control" name="comentario" id="comentario" value="---------------"></td>
            </tr> 
        </table>
        <div class="row">
            <div>
                <button type="button" id="agregarV" class="btn btn-success float-right">Agregar producto</button>
            </div>
        </div>
        <div class="form-group">
            <div class="table-responsive col-md-12">
                <table id="detallesV" class="table table-striped">
                    <h4 class="card-title">Detalles de la venta</h4>
                    <thead>
                        <tr>
                            <th>Eliminar</th>
                            <th>Cantidad</th>
                            <th>Artículo</th>
                            <th>Precio/V (Q.)</th>
                            <th>Extra (Q.)</th>
                            <th>Descuento (Q.)</th>
                            <th>Comentario</th>
                            <th>Sub Total (Q.)</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="7">
                                <p align="right">TOTAL VENTA S/IMPUESTO</p>
                            </th>
                            <th>
                                <p align="center"><span id="totalVenta">Q. 0.00</span></p>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="7">
                                <p align="right">TOTAL IMPUESTO (12%)</p>
                            </th>
                            <th>
                                <p align="right"><span id="total_impuesto_html">Q. 0.00</span><input type="hidden" name="tax" id="total_impuesto"></p>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="7">
                                <p align="right">TOTAL A PAGAR</p>
                            </th>
                            <th>
                                <p align="right"><span align="right" id="total_pagar_html">Q. 0.00</span><input type="hidden" name="total" id="total_pagar"></p>
                            </th>
                        <tr>
                    </tfoot>
                </table> 
            </div> 
        </div>
        <table> 
            <tr>
                <td>         
                    <a id="guardarC" class="btn btn-outline-primary" onclick="return ventaInsert()">Resgistrar</a>
                </td>   
                <td>                     
                    <a class="btn btn-outline-danger" href="{{ route('comprasIndex') }}" role="button">Cancelar</a>
                </td>
            </tr> 
        </table>    
    </form>       
@stop
@section('css')
@stop

@section('js')
<script src="{{ asset('js/ventas/ventaCreate.js') }}"></script>
<script src="{{ asset('js/select.js') }}"></script>
@stop


