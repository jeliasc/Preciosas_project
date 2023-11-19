function productoEdit(id) {
    $.ajax({
        type: "get",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "editarProducto/"+id,
        data: {id},

        success: function (response) {
            if(response.error){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.mensaje,
                    footer: ''
                  });  

            }else{ 
                let producto = response.producto;
                let categorias = response.categorias;
                let proveedores = response.proveedores;
                let foto = response.foto;
                let selected ='';
                let textoCat = '';
                let textoProv = '';
                let imagen = '';
                let texto = '';

                console.log(producto);
                console.log(categorias);
                console.log(proveedores);
                console.log(foto);

                $("#categoria_id_edit" ).empty();
                textoCat += '<option value="">Elija una opcion</option>';            
                $("#proveedor_id_edit" ).empty();
                textoProv += '<option value="">Elija una opcion</option>';  

                categorias.forEach(function(categoria) {
                    if (producto) {
                        if(categoria.id == producto.categoria_id){
                                selected = 'selected';
                                textoCat += '<option '+selected+' value="'+categoria.id+'">'+categoria.nombre+'</option>';

                        }else{
                            textoCat += '<option value="'+categoria.id+'">'+categoria.nombre+'</option>';
                        }
                    }
                });  
                proveedores.forEach(function(proveedor) {
                    if (producto) {
                        if(proveedor.id == producto.proveedor_id){
                                selected = 'selected';
                                textoProv += '<option '+selected+' value="'+proveedor.id+'">'+proveedor.nombre+'</option>';

                        }else{
                            textoProv += '<option value="'+proveedor.id+'">'+proveedor.nombre+'</option>';
                        }
                    }
                });     

                $("#foto_edit" ).empty();
                if (foto) {
                    $('#imagen').html('');
                    imagen += '<img src="/images/'+foto.ruta_foto+'" width="150" />';

                }else{
                    texto = 'no_img.png';
                    imagen += '<img src="/images/'+texto+'" width="150" />';
                } 

                $('#id').val(producto.id).hide();
                $('#code_edit').val(producto.code);
                $('#nombre_edit').val(producto.nombre);
                $('#precio_edit').val(producto.precio);
                $('#descripcion_edit').val(producto.descripcion);
                $("#categoria_id_edit" ).append(textoCat);
                $("#proveedor_id_edit" ).append(textoProv);
                $("#foto_edit" ).append(imagen);
                $('#updateModal').modal('show');
            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);

        }
    })   
}
       
function productoUpdate() {
    let frm = document.getElementById('editForm');
    var form = new FormData(frm);
    var id = $('#id').val();

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "actualizarProducto/"+id,
        data: form,  
        contentType: false,
        processData: false,  

        success: function (response) {
            if(response.error){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.mensaje,
                    footer: ''
                  });   

            }else{
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje,
                    showDenyButton: false,
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    } else if (result.isDenied) {
                        location.reload();
                    }
                })
            }
            
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);

        }
    })                       
}
