function userCreate() {
    $.ajax({
        type: "get",
        dataType: "json",
        url: "crearUsuario",

        success: function (response) {
            if(response.error){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.mensaje,
                    footer: ''
                  });  

            }else{
                const roles = response.roles;

                let texto = '';

                console.log(roles);

                $("#role_id_create" ).empty();
                texto += '<option value="">Elija una opcion</option>';            

                roles.forEach(role => {
                    if(role.id != ''){
                        texto += '<option value="'+role.id+'">'+role.name+'</option>';
                    }
                });
                $("#role_id_create" ).append(texto);
                $('#createModal').modal('show');   

            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);

        }
    })   
}

function userInsert() {

    var form = $('#createForm').serialize();

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "insertarUsuario",
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