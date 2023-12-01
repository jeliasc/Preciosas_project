function clienteEdit(id) {
    $.ajax({
        type: "get",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "editarCliente/"+id,
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
                let cliente = response.cliente;

                $('#id').val(cliente.id).hide();
                $('#nit_edit').val(cliente.nit);
                $('#nombre_edit').val(cliente.nombre);
                $('#direccion_edit').val(cliente.direccion);
                $('#telefono_edit').val(cliente.telefono);
                $('#email_edit').val(cliente.email);
                $('#updateModal').modal('show'); 
            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);

        }
    })   
}
       
function clienteUpdate() {
    var form = $('#editForm').serialize();
    var id = $('#id').val();

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "actualizarCliente/"+id,
        data: form,   

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
