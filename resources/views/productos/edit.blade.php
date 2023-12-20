 <!-- Modal editar producto -->       

 <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content my-sm-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar Artículo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" enctype="multipart/form-data">
                @CSRF
                <div class="table table-responsive">
                        <table class="table">
                            <tbody>
                                <tr hidden>
                                    <td><label hidden>Id</label></td>
                                    <td><input class="form-control" type="text" id="id" name="id" hidden></td>
                                </tr>
                                <tr>
                                    <div id="foto_edit" align="center">
                                    </div>
                                </tr>
                                <tr>
                                    <td align="center"><input type="file" id="foto_id_edit" name="foto_id"></td>
                                </tr>
                                <tr>
                                    <td><label>Código</label></td>
                                    <td><input class="form-control" type="text" id="code_edit" name="code">
                                        @error('code')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Nombre</label></td>
                                    <td><input class="form-control" type="text" id="nombre_edit" name="nombre">
                                        @error('nombre')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Precio</label></td>
                                    <td><input class="form-control" type="text" id="precio_edit" name="precio">
                                        @error('precio')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Descripción</label></td>
                                    <td><input class="form-control" type="text" id="descripcion_edit" name="descripcion" value="---------------">
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
                                        <select name="categoria_id" id="categoria_id_edit" class="form-select">
                                            <option value=""></option>
                                        </select> 
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Proveedor</label></td>
                                    <td>
                                        <select name="proveedor_id" id="proveedor_id_edit" class="form-select">
                                            <option value=""></option>
                                        </select> 
                                    </td>
                                </tr>
                            </tbody> 
                            <tfoot>
                                <tr> 
                                    <td style="text-align: right" colspan="2">      
                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>         
                                        <a class="btn btn-outline-primary" onclick="return productoUpdate()">Enviar</a>
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

<!-- Fin de modal crear producto -->   