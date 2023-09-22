function userEdit(id) {
    $.ajax({
        type: "get",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "editarUsuario/"+id,
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
                const users = response.data;
                const roles = response.roles;
                let texto = '';
                let selected = '';

                $("#role_id" ).empty();
                texto += '<option value="">Elija una opcion</option>';
                $('#id').val(users.id).hide();
                $('#name').val(users.name);
                $('#email').val(users.email);

                roles.forEach(role => {
                    if(users.id == role.id){
                        selected = 'selected';
                    }
                    texto += '<option '+selected+' value="'+role.id+'">'+role.name+'</option>';
                    
                });
                $("#role_id" ).append(texto);
                $('#updateModal').modal('show');   

            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);

        }
    })   
}


       
function userUpdate() {

    var form = $('#editForm').serialize();
    var id = $('#id').val();

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "usuariosUpdate/"+id,
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



   






