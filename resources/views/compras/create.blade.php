<!-- Modal crear compras -->   
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content my-sm-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                @CSRF
                    <div class="table table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><label>No. de Factura</label></td>
                                    <td><input type="text" class="form-control" name="no_fac" id="no_fac"></td>
                                </tr>
                                <tr>
                                    <td><label>Artículo</label></td>
                                    <td>
                                        <select name="producto_id" id="producto_id" class="form-select">
                                            <option value=""></option>
                                        </select> 
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Proveedor</label></td>
                                    <td>
                                        <select name="proveedor_id" id="proveedor_id" class="form-select">
                                            <option value=""></option>
                                        </select> 
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Cantidad</label></td>
                                    <td><input type="number" class="form-control" name="cantidad" id="cantidad"></td>
                                </tr>
                                <tr>
                                    <td><label>Precio/C</label></td>
                                    <td><input type="number" class="form-control" name="precio" id="precio"></td>
                                </tr>
                                <tr>
                                    <td><label>Impuesto</label></td>
                                    <td><input type="number" class="form-control" name="tax" id="tax" value="" placeholder="12%" disabled></td>
                                </tr>
                            </tbody> 
                        </table>
                        <div class="row">
                            <div>
                                <button type="button" id="agregarC" class="btn btn-outline-success float-right">Agregar producto</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="table-responsive">
                                <table id="detallesC" class="table table-lg-striped">
                                    <h4 class="card-title">Detalles de la compra</h4>
                                    <thead>
                                        <tr>
                                            <th>Eliminar</th>                        
                                            <th>Artículo</th>
                                            <th>Precio (Q.)</th>
                                            <th>Cantidad</th>
                                            <th>Sub Total (Q.)</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">
                                                <p align="right">TOTAL S/IMPUESTO</p>
                                            </th>
                                            <th>
                                                <p align="right"><span id="totalCompra">Q. 0.00</span></p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4">
                                                <p align="right">TOTAL IMPUESTO (12%)</p>
                                            </th>
                                            <th>
                                                <p align="right"><span id="total_impuesto_html">Q. 0.00</span><input type="hidden" name="tax" id="total_impuesto"></p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4">
                                                <p align="right">TOTAL A PAGAR</p>
                                            </th>
                                            <th>
                                                <p align="right"><span align="right" id="total_pagar_html">Q. 0.00</span><input type="hidden" name="total" id="total_pagar"></p>
                                            </th>
                                        <tr>
                                    </tfoot>
                                </table> 
                                <table> 
                                    <tr>
                                        <td>         
                                            <a id="guardarC" class="btn btn-outline-primary" onclick="return compraInsert()">Resgistrar</a>
                                        </td>   
                                        <td>                     
                                            <a class="btn btn-outline-danger" href="{{ route('comprasIndex') }}" role="button">Cancelar</a>
                                        </td>
                                    </tr> 
                                </table>   
                            </div>
                        </div>                  
                    </div>
                </form>
            </div>
        </div>
    </div>
 </div>

<!-- Fin de modal crear cliente -->   

 