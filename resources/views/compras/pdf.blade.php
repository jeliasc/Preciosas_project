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

        #fac,
        #fu,
        #fp{
            color:#FFFFFF;
            font-size:15px;
        }

        #factProveedor{
            padding: 10px;
            background: #33AFFF;
            text-align: center;
        }

        #proveedor{
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
            text-align: center;
            border-bottom: 5px solid #FFFFFF;
        }

        #factProducto {
            text-align: center;
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
        }

        #factProducto thead{
            padding: 20px;
            background: #33AFFF;
            text-align: center;
            border-bottom: 2px solid #FFFFFF;
        }
       
      </style>
    <body>
        <h3>Reporte de compra</h3>
        <header>
            <div>
                <table id="datos">
                    <thead>
                        <tr>
                            <td id="factProveedor"> <h3>Proveedor</h3></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>
                                <p id="proveedor">Nombre {{$compra->proveedor->nombre}} <br>
                                    Nit {{$compra->proveedor->nit}}<br>
                                    E-mail {{$compra->proveedor->email}}<br>
                                    Telefono {{$compra->proveedor->telefono}}<br>
                                    Direccion {{$compra->proveedor->direccion}} <br>
                                </p>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="fact">
                <h6>No. Factura<br> {{$compra->no_factura}}</h6> 
            </div>
        </header>
        <br>
        <br>
        <section>
            <div>
                <table border=1; id="factUser">
                    <thead>
                        <tr id="fu">
                            <th>Usuario</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="user">
                            <td>{{$compra->user->name}}</td>
                            <td>{{date("d/m/Y H:i:s", strtotime($compra->fecha))}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <section>
            <div>
                <table border=1; id="factProducto">
                    <thead>
                        <tr id="fp">
                            <th>Cantidad</th>
                            <th>Artículo</th>
                            <th>Precio/C (Q)</th>
                            <th>SubTotal (Q)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalleCompras as $detalleCompra)
                        <tr>
                            <td>{{$detalleCompra->cantidad}}</td>
                            <td>{{$detalleCompra->producto->nombre}}</td>
                            <td>Q. {{$detalleCompra->precio}}</td>
                            <td>Q. {{number_format( $detalleCompra->cantidad*$detalleCompra->precio,2)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">
                                <p align="right">SubTotal (12%)</p>
                            </th>
                            <th>
                                <p align="center">Q. {{number_format($compra->total-$compra->tax,2)}}</p>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <p align="right">TOTAL IMPUESTO (12%)</p>
                            </th>
                            <th>
                                <p align="center">Q.{{number_format($compra->tax,2)}} </p>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="3">
                                <p align="right">TOTAL</p>
                            </th>
                            <th>
                                <p align="center">Q. {{number_format($compra->total,2)}}</p>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    </body>
</html>
