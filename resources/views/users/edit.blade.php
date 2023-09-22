    <!-- Modal editar usuario -->   

   <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content my-sm-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                @CSRF
                <div class="table table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><label hidden>Id</label></td>
                                    <td><input class="form-control" type="text" id="id" name="id"></td>
                                </tr>
                                <tr>
                                    <td><label>Nombre</label></td>
                                    <td><input class="form-control" type="text" id="name" name="name"></td>
                                </tr>
                                <tr>
                                    <td><label>Role</label></td>
                                    <td>
                                        <select name="role_id" id="role_id" class="form-select" required>
                                        </select> 
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>E-mail</label></td>
                                    <td><input type="text" name="email" id="email" class="form-control"></td>
                                </tr>
                            </tbody> 
                            <tfoot>
                                <tr> 
                                    <td style="text-align: right" colspan="2">      
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>         
                                        <a class="btn btn-primary" onclick="return userUpdate()">Actualizar</a>
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

<!-- Fin de modal editar usuario -->    
