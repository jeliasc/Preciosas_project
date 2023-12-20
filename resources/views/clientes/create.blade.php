 <!-- Modal crear cliente -->       

 <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content my-sm-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Crear cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createForm">
                @CSRF
                    <div class="table table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><label>Nit</label></td>
                                    <td><input class="form-control" type="text" id="nit" name="nit">
                                        @error('nit')
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
                                </tr><tr>
                                    <td><label>Dirección</label></td>
                                    <td><input class="form-control" type="text" id="direccion" name="direccion">
                                        @error('direccion')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr><tr>
                                    <td><label>Teléfono</label></td>
                                    <td><input class="form-control" type="text" id="telefono" name="telefono">
                                        @error('telefono')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr><tr>
                                    <td><label>E-mail</label></td>
                                    <td><input class="form-control" type="email" id="email" name="email">
                                        @error('email')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </td>
                                </tr>
                            </tbody> 
                            <tfoot>
                                <tr> 
                                    <td style="text-align: right" colspan="2">      
                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>         
                                        <a class="btn btn-outline-primary" onclick="return clienteInsert()">Enviar</a>
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

<!-- Fin de modal crear cliente -->   