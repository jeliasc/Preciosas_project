<h3 align="center">Reporte de Compras</h3>   
    {!! Form::open(['url' => ' ']) !!}
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
                <strong>{{$compras->count()}} <br><br></strong>
            </div>
        </div>      
        <div class="col-12 col-md-4 text-center">
            <span>Total de Egresos</span>
            <div class="form-group">
                <strong>Q. {{$total}} <br><br></strong>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    <table id="myTable" class="table table-striped dt-responsive nowrap" style="width:100%">
        <thead>
            <tr align="left">
                <th scope="col">No. Factura</th>
                <th scope="col">Usuario</th>
                <th scope="col">Proveedor</th>
                <th scope="col">Fecha</th>
                <th scope="col">Total</th>
                <th scope="col">Impuesto</th>
                <th scope="col">Estado</th>
            </tr>
        </thead>
        <tbody>
            @if($compras)
                @foreach($compras as $compra)
                    <tr>
                        <td>{{$compra->no_factura}}</td>
                        <td>{{$compra->user->name}}</td>
                        <td>{{$compra->proveedor->nombre}}</td>
                        <td>{{date("d/m/Y H:i:s", strtotime($compra->fecha))}}</td>
                        <td>{{$compra->total}}</td>
                        <td>{{$compra->tax}}</td>
                        <td>{{$compra->estado->nombre}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>       
    </table>
    <script src="{{ asset('js/preciosas/compras/fecha.js') }}"></script>

        