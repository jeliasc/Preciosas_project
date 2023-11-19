 <!-- Modal crear producto -->       

 <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content my-sm-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForm" enctype="multipart/form-data">
                @CSRF
                <div class="table table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><label>Código</label></td>
                                    <td><input class="form-control" type="text" id="code" name="code">
                                        @error('code')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Nombre</label></td>
                                    <td><input class="form-control" type="text" id="nombre" name="nombre">
                                        @error('nombre')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Precio</label></td>
                                    <td><input class="form-control" type="text" id="precio" name="precio">
                                        @error('precio')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Descripción</label></td>
                                    <td><input class="form-control" type="text" id="descripcion" name="descripcion" value="---------------">
                                        @error('descripcion')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Categoría</label></td>
                                    <td>
                                        <select name="categoria_id" id="categoria_id" class="form-select">
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
                                    <td><label>Imagen</label></td>
                                    <td><input type="file" id="foto_id" name="foto_id"></td>
                                </tr>
                            </tbody> 
                            <tfoot>
                                <tr> 
                                    <td style="text-align: right" colspan="2">      
                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>         
                                        <a class="btn btn-outline-primary" onclick="return productoInsert()">Enviar</a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

<!-- Fin de modal crear proveedor -->   