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
                        <div class="container-fluid">
                            <div class="text-center mb-3">
                                <div id="foto_edit"></div>
                                <input type="file" id="foto_id_edit" name="foto_id" class="form-control mt-2">
                            </div>

                            <input type="hidden" id="id" name="id">

                            <div class="mb-3">
                                <label>Código</label>
                                <input class="form-control" type="text" id="code_edit" name="code">
                            </div>

                            <div class="mb-3">
                                <label>Nombre</label>
                                <input class="form-control" type="text" id="nombre_edit" name="nombre">
                            </div>

                            <div class="mb-3">
                                <label>Precio</label>
                                <input class="form-control" type="text" id="precio_edit" name="precio">
                            </div>

                            <div class="mb-3">
                                <label>Descripción</label>
                                <input class="form-control" type="text" id="descripcion_edit" name="descripcion">
                            </div>

                            <div class="mb-3">
                                <label>Categoría</label>
                                <select name="categoria_id" id="categoria_id_edit" class="form-select w-100"></select>
                            </div>

                            <div class="mb-3">
                                <label>Proveedor</label>
                                <select name="proveedor_id" id="proveedor_id_edit" class="form-select w-100"></select>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-outline-primary" onclick="productoUpdate()">Enviar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>

<!-- Fin de modal crear producto -->   