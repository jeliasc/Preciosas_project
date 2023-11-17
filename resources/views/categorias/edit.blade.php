    <!-- Modal editar categoría -->   

    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content my-sm-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                    @CSRF
                    <div class="table table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr hidden>
                                        <td><label hidden>Id</label></td>
                                        <td><input class="form-control" type="text" id="id" name="id" hidden></td>
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
                                </tbody> 
                                <tfoot>
                                    <tr> 
                                        <td style="text-align: right" colspan="2">      
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>         
                                            <a class="btn btn-outline-primary" onclick="return categoriaUpdate()">Enviar</a>
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
    
    <!-- Fin de editar categoría -->    
