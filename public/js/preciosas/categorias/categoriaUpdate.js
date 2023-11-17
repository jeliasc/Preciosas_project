function categoriaEdit(id) {
    $.ajax({
        type: "get",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "editarCategoria/"+id,
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
                let categoria = response.categoria;

                console.log(categoria);

                $('#id').val(categoria.id).hide();
                $('#nombre').val(categoria.nombre);
                $('#updateModal').modal('show'); 
            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);

        }
    })   
}
       
function categoriaUpdate() {
    var form = $('#editForm').serialize();
    var id = $('#id').val();

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "actualizarCategoria/"+id,
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
