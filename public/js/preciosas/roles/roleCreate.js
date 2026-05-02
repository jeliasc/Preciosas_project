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
                var permisosContainer = $('#permisos_id');
                let texto = '';

                permisosContainer.empty();

                permissions.forEach(permission => {
                    texto = $(
                        '<label>' +
                            '<input type="checkbox" class="permiso-check" name="permissions[]" value="' + permission.id + '"> ' +
                            permission.name +
                        '</label><br>'
                    );

                    permisosContainer.append(texto);
                });

                $("#seleccionar_todos_permisos").prop("checked", false);

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

$(document).on('change', '#seleccionar_todos_permisos', function () {
    $('.permiso-check').prop('checked', $(this).is(':checked'));
});

$(document).on('change', '.permiso-check', function () {
    let total = $('.permiso-check').length;
    let marcados = $('.permiso-check:checked').length;

    $('#seleccionar_todos_permisos').prop('checked', total === marcados);
});