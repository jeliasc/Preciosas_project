function roleCreate() {
    $.ajax({
        type: "get",
        dataType: "json",
        url: "crearRole",

        success: function (response) {
            if(response.error){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.mensaje,
                    footer: ''
                  });  

            }else{
                const permissions = response.permissions;

                console.log(permissions);

                var permisosContainer = $('#permisos_id');
                let texto = '';

                permisosContainer.empty();

                permissions.forEach(permission => {
                    texto = $('<input type="checkbox" name="permissions[]" value="' + permission.id + '"> ' + permission.name + '<br>');
                    permisosContainer.append(texto);
                });

                $('#createModal').modal('show');   
            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);

        }
    })   
}

function roleInsert() {

    var form = $('#createForm').serialize();

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "insertarRole",
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