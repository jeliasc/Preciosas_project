function roleEdit(id) {
    $.ajax({
        type: "get",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "editarRole/"+id,
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
                const role = response.role;
                const rolePermissions= response.rolePermissions;

                console.log(role);
                console.log(rolePermissions);


                let role_id = '';
                role_id = rolePermissions;

                $('#id').val(role.id).hide();
                $('#name').val(role.name);

                let check = "";
                let checkboxesContainer = $('#edit_permisos_id');
                checkboxesContainer.empty();
                let texto = "";

                rolePermissions.forEach(function(permission) {
                    if (permission.role_id != null) {
                        check = "checked";
                    }else{
                        check = "";
                    }
                    texto += '<div class="form-check"><input type="checkbox" '+check+'  name="permissions[]" value="'+ permission.id +'" class="name" > ' + permission.name + '</div>';
                });

                checkboxesContainer.append(texto);
                $('#updateModal').modal('show'); 
            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);

        }
    })   
}
       
function roleUpdate() {

    var form = $('#editForm').serialize();
    var id = $('#id').val();

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "actualizarRole/"+id,
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



   






