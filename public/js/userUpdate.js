function userEdit(id) {
    $.ajax({
        type: "get",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#cartera_token').val()},
        url: "editarUsuario/"+id,
        data: {id},
        // beforeSend: function () {
        //     //$('.loader').show();
        //   },
        success: function (response) {
            console.table(response);
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
                $('#name').val(users.name);
                $('#email').val(users.email);
                roles.forEach(role => {
                    if(role.id == users.role_id){
                        selected = 'selected';
                    }
                    texto += '<option '+selected+' value="'+role.id+'">'+role.name+'</option>';
                });
                $("#role_id" ).append(texto);
                $('#updateModal').modal('show');
                //pintar los datos en html
   
            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);
        }
    })
    // }).always(function(data) {
    //     //$('.loader').hide();
    // });    
}

function userUpdate(id) {
    $.ajax({
        type: "get",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#cartera_token').val()},
        url: "editarUsuario/"+id,
        data: {id},
        // beforeSend: function () {
        //     //$('.loader').show();
        //   },
        success: function (response) {
            console.table(response);
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
                $('#name').val(users.name);
                $('#email').val(users.email);
                roles.forEach(role => {
                    if(role.id == users.role_id){
                        selected = 'selected';
                    }
                    texto += '<option '+selected+' value="'+role.id+'">'+role.name+'</option>';
                });
                $("#role_id" ).append(texto);
                $('#updateModal').modal('show');
                //pintar los datos en html
   
            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);
        }
    })
    // }).always(function(data) {
    //     //$('.loader').hide();
    // });    
}





