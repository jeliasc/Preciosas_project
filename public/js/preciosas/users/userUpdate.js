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
                const user = response.user;
                const roles = response.roles;
                const role_user= response.role_user;

                console.log(roles);
                console.log(role_user);
                console.log(user);

                let selected = '';
                let texto = '';
                let role_id = '';
                role_id = role_user;

                $("#role_id" ).empty();
                texto += '<option value="">Elija una opción</option>';
                $('#id').val(user.id).hide();
                $('#name').val(user.name);
                $('#email').val(user.email);

                roles.forEach(function(role) {
                    if (role_id != null && role_id != '') {
                        if(role.id == role_user.id_role){
                                selected = 'selected';
                                texto += '<option '+selected+' value="'+role.id+'">'+role.name+'</option>';
                        }else{
                            texto += '<option value="'+role.id+'">'+role.name+'</option>';
                        }
                    }else{
                        texto += '<option value="'+role.id+'">'+role.name+'</option>';
                    }
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
        url: "actualizarUsuarios/"+id,
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



   






