function compraDelete(id) {

    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta compra será anulada",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "eliminarCompra/" + id,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function (response) {
                    if (response.error) {
                        Swal.fire('Error', response.mensaje, 'error');
                    } else {
                        Swal.fire('Compra anulada', response.mensaje, 'success')
                            .then(() => location.reload());
                    }
                },

                error: function (jqXHR) {
                    console.log(jqXHR.responseText);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo anular la compra'
                    });
                }
            });
        }
    });
}