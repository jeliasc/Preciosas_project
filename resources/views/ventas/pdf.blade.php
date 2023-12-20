<!DOCTYPE html>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        #datos{
            float: left;
            margin-top: 0%;
            margin-left: 2%;
            margin-right: 2%;
        }

        #encabezado{
            text-align: center;
            margin-left: 35%;
            margin-right: 35%;
            font-size: 15px;
        }

        #fact{
            float: right;
            margin-top: 2%;
            margin-left: 2%;
            margin-right: 2%;
            font-size: 20px;
        }

        section{
            clear: left;
        }

        #user{
            text-align: center;
            width: 40%;
            border-collapse:collapse;
            border-spacing: 0;
            margin-bottom: 1px;
        }

      
        #fu,
        #fp{
            color:#FFFFFF;
            font-size:15px;
        }

        #factCliente{
            padding: 10px;
            background: #33AFFF;
            text-align: center;
        }

        #cliente{
            width: 100%;
            border-collapse:collapse;
            border-spacing: 0;
            margin-bottom: 1px;
        }

        #factUser{
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px;
        }

        #factUser thead{
            padding: 20px;
            background: #33AFFF;
            border-bottom: 5px solid #FFFFFF;
        }

        #factProducto {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
        }

        #factProducto thead{
            padding: 20px;
            text-align: center;
            background: #33AFFF;
            text-align: center;
            border-bottom: 2px solid #FFFFFF;
        }
       
      </style>
    <body>
        <h3>Reporte de venta</h3>
        <header>
            <div>
                <table id="datos">
                    <thead>
                        <tr>
                            <td id="factCliente"> <h3>Cliente</h3></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>
                                <p id="cliente">{{$venta->cliente->nombre}} <br>
                                                {{$venta->cliente->direccion}}
                                </p>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="fact">
                <h6>Pedido no. <br> {{$venta->numero_factura}}</h6> 
            </div>
        </header>
        <br>
        <br>
        <section>
            <div>
                <table border=1 id="factUser">
                    <thead>
                        <tr id="fu">
                            <th>Usuario</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="user">
                            <td>{{$venta->user->name}}</td>
                            <td>{{$venta->fecha}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <section>
            <div>
                <table border=1 id="factProducto">
                    <thead>
                        <tr id="fp">
                            <th>Cantidad</th>
                            <th>Artículo</th>
                            <th>Precio/V (Q)</th>
                            <th>Descuento (Q)</th>
                            <th>Extra (Q)</th>
                            <th>Comentario</th>
                            <th>SubTotal (Q)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalleVentas as $detalleVenta)
                        <tr>
                            <td>{{$detalleVenta->cantidad}}</td>
                            <td>{{$detalleVenta->producto->nombre}}</td>
                            <td>Q. {{$detalleVenta->precio}}</td>
                            <td>Q. {{$detalleVenta->descuento}}</td>
                            <td>Q. {{$detalleVenta->extra}}</td>
                            <td>{{$detalleVenta->comentario}}</td>
                            <td>Q. {{number_format((($detalleVenta->cantidad * $detalleVenta->precio) + $detalleVenta->extra) - ($detalleVenta->descuento),2)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6">
                                <p align="right">SubTotal Venta</p>
                            </th>
                            <th>
                                <p align="center">Q. {{number_format($venta->total-$venta->envio,2)}}</p>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="6">
                                <p align="right">Envío</p>
                            </th>
                            <th>
                                <p align="center">Q.{{number_format($venta->envio,2)}} </p>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="6">
                                <p align="right">Total</p>
                            </th>
                            <th>
                                <p align="center">Q. {{number_format($venta->total,2)}}</p>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </body>
</html>
