function productoCreate() {
    $.ajax({
        type: "get",
        dataType: "json",
        url: "crearArticulo",

        success: function (response) {
            if(response.error){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.mensaje,
                    footer: ''
                  });  

            }else{
                let categorias = response.categorias;
                let proveedores = response.proveedores;
                let textoCat = '';
                let textoProv = '';

                $("#categoria_id" ).empty();
                textoCat += '<option value="">Elija una opcion</option>';            

                $("#proveedor_id" ).empty();
                textoProv += '<option value="">Elija una opcion</option>';            

                categorias.forEach(categoria => {
                    if(categoria.id != ''){
                        textoCat += '<option value="'+categoria.id+'">'+categoria.nombre+'</option>';
                    }
                });

                proveedores.forEach(proveedor=> {
                    if(proveedor.id != ''){
                        textoProv += '<option value="'+proveedor.id+'">'+proveedor.nombre+'</option>';
                    }
                });

                $("#categoria_id" ).append(textoCat);
                $("#proveedor_id" ).append(textoProv);
                $('#createModal').modal('show');   

            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);
            $('#createModal').modal('show');   
        }
    })   
}

function productoInsert() {
    let frm = document.getElementById('createForm');
    var form = new FormData(frm);

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "insertarArticulo",
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