    <!-- Modal crear usuario -->   

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content my-sm-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createForm">
                    @CSRF
                    <div class="table table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td><label>Nombre</label></td>
                                        <td><input class="form-control" type="text" id="name_create" name="name">
                                            @error('name')
                                                <small> 
                                                    <strong>{{$message}}</strong>
                                                </small>
                                            @enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>E-mail</label></td>
                                        <td><input type="text" name="email" id="email_create" class="form-control" required>
                                            @error('email')
                                                <small> 
                                                    <strong>{{$message}}</strong>
                                                </small>
                                            @enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Password</label></td>
                                        <td><input name="password" id="password_create" class="form-control" type="password" autocomplete="off"></td>
                                        @error('password')
                                            <small> 
                                                <strong>{{$message}}</strong>
                                            </small>
                                        @enderror
                                    </tr>
                                    <tr>
                                        <td><label>Role</label></td>
                                        <td>
                                            <select name="role_id" id="role_id_create" class="form-select">
                                                <option value=""></option>
                                            </select> 
                                        </td>
                                    </tr>
                                </tbody> 
                                <tfoot>
                                    <tr> 
                                        <td style="text-align: right" colspan="2">      
                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>         
                                            <a class="btn btn-outline-primary" onclick="return userInsert()">Enviar</a>
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
    
    <!-- Fin de modal crear usuario -->    
