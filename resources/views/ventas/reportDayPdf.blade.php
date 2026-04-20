<h3 align="center">Reporte de Ventas</h3>
     <div class="row">
        <div class="col-12 col-md-4 text-center">
            <span>Fecha de Consulta</span>
            <div class="form-group">
                <strong>{{\Carbon\Carbon::now()->format("d/m/Y H:i:s")}} <br><br></strong>
            </div>
        </div>
        <div class="col-12 col-md-4 text-center">
            <span>Cantidad de Registros</span>
            <div class="form-group">
                <strong>{{$ventas->count()}} <br><br></strong>
            </div>
        </div>      
        <div class="col-12 col-md-4 text-center">
            <span>Total de Ingresos</span>
            <div class="form-group">
                <strong>Q. {{$total}} <br><br></strong>
            </div>
        </div>
    </div>
    <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr align="left">
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
                        <td>{{$venta->cliente->nit}}</td>
                        <td>{{date("d/m/Y H:i:s", strtotime($venta->fecha))}}</td>
                        <td>{{$venta->total}}</td>
                        <td>{{$venta->tax}}</td>
                        <td>{{$venta->estado->nombre}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>       
    </table>
    <script src="{{ asset('js/preciosas/ventas/fecha.js') }}"></script>

        