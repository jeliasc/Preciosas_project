function ventaDelete(id) {

    Swal.fire({
        title: '¿Anular venta?',
        text: 'Esta acción anulará la venta y devolverá el stock.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                type: "POST",
                dataType: "json",
                url: "eliminarVenta/" + id,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },

                success: function (response) {
                    if (response.error) {
                        Swal.fire('Error', response.mensaje, 'error');
                    } else {
                        Swal.fire('Venta anulada', response.mensaje, 'success')
                            .then(() => location.reload());
                    }
                },

                error: function () {
                    Swal.fire('Error', 'No se pudo anular la venta', 'error');
                }
            });
        }
    });

    return false;
}