$(document).ready(function () {
    $("#agregarC").click(function () { 
        agregarC();
    });

    $("#guardarC").hide();
});

var cont = 0;
var total = 0;

function compraCreate() {
    $.ajax({
        type: "get",
        dataType: "json",
        url: "crearCompra",

        success: function (response) {
            if (response.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.mensaje
                });  
            } else {
                let proveedores = response.proveedores;
                let productos = response.productos;
                let textoProd = '';
                let textoProv = '';     

                $("#proveedor_id").empty();
                textoProv += '<option value="">Elija una opcion</option>';

                $("#producto_id").empty();
                textoProd += '<option value="">Elija una opcion</option>';  

                proveedores.forEach(proveedor => {
                    if (proveedor.id != '') {
                        textoProv += '<option value="' + proveedor.id + '">' + proveedor.nombre + '</option>';
                    }
                });

                productos.forEach(producto => {
                    if (producto.id != '') {
                        textoProd += '<option value="' + producto.id + '">' + producto.nombre + '</option>';
                    }
                });

                $("#proveedor_id").append(textoProv);
                $("#producto_id").append(textoProd);

                limpiarCompra();
                limpiarDetalleCompra();

                $('#createModal').modal('show');  
            }
        },

        error: function (jqXHR, estado, error) {
            console.log(estado);
            console.log(error);
            $('#createModal').modal('show');   
        }
    });
}

function agregarC() {
    let producto_id = $("#producto_id").val();
    let producto = $("#producto_id option:selected").text();
    let cantidad = parseInt($("#cantidad").val());
    let precio = parseFloat($("#precio").val());

    if (producto_id === "" || isNaN(cantidad) || cantidad <= 0 || isNaN(precio) || precio <= 0) {
        Swal.fire('Debe llenar todos los campos correctamente');
        return;
    }

    let filaExistente = $("#detallesC tr[data-producto-id='" + producto_id + "']");

    if (filaExistente.length > 0) {
        let inputCantidad = filaExistente.find('input[name="cantidad[]"]');
        let nuevaCantidad = parseInt(inputCantidad.val()) + cantidad;

        inputCantidad.val(nuevaCantidad);
        filaExistente.find(".cantidad-visible").val(nuevaCantidad);

        actualizarSubtotalFila(filaExistente);

    } else {
        let fila = '';

        fila += '<tr class="selected" id="fila' + cont + '" data-producto-id="' + producto_id + '">';

        fila += '<td>';
        fila += '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarCompra(' + cont + ');">';
        fila += '<i class="fa fa-times"></i>';
        fila += '</button>';
        fila += '</td>';

        fila += '<td>';
        fila += '<input type="hidden" name="producto_id[]" value="' + producto_id + '">';
        fila += producto;
        fila += '</td>';

        fila += '<td>';
        fila += '<input type="hidden" name="precio[]" value="' + precio.toFixed(2) + '">';
        fila += '<input class="form-control precio-visible" type="number" value="' + precio.toFixed(2) + '" disabled>';
        fila += '</td>';

        fila += '<td>';
        fila += '<div class="input-group">';
        fila += '<button type="button" class="btn btn-secondary btn-sm" onclick="cambiarCantidadCompra(' + cont + ', -1)">-</button>';
        fila += '<input type="hidden" name="cantidad[]" value="' + cantidad + '">';
        fila += '<input class="form-control text-center cantidad-visible" type="number" value="' + cantidad + '" disabled>';
        fila += '<button type="button" class="btn btn-secondary btn-sm" onclick="cambiarCantidadCompra(' + cont + ', 1)">+</button>';
        fila += '</div>';
        fila += '</td>';

        fila += '<td align="right" class="subtotal-compra">';
        fila += 's/t Q. ' + (cantidad * precio).toFixed(2);
        fila += '</td>';

        fila += '</tr>';

        $('#detallesC').append(fila);
        cont++;
    }

    limpiarDetalleCompra();
    recalcularTotalCompra();
    evaluarCompra();

    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Producto agregado',
        showConfirmButton: false,
        timer: 1200
    });
}

function cambiarCantidadCompra(index, cambio) {
    let fila = $("#fila" + index);

    if (fila.length === 0) {
        return;
    }

    let inputCantidad = fila.find('input[name="cantidad[]"]');
    let nuevaCantidad = parseInt(inputCantidad.val()) + cambio;

    if (nuevaCantidad <= 0) {
        eliminarCompra(index);
        return;
    }

    inputCantidad.val(nuevaCantidad);
    fila.find(".cantidad-visible").val(nuevaCantidad);

    actualizarSubtotalFila(fila);
    recalcularTotalCompra();
    evaluarCompra();
}

function eliminarCompra(index) {
    $("#fila" + index).remove();

    recalcularTotalCompra();
    evaluarCompra();
}

function actualizarSubtotalFila(fila) {
    let cantidad = parseInt(fila.find('input[name="cantidad[]"]').val());
    let precio = parseFloat(fila.find('input[name="precio[]"]').val());

    let subtotal = cantidad * precio;

    fila.find(".subtotal-compra").html("s/t Q. " + subtotal.toFixed(2));
}

function recalcularTotalCompra() {
    total = 0;

    $("#detallesC tr").each(function () {
        let cantidad = parseInt($(this).find('input[name="cantidad[]"]').val());
        let precio = parseFloat($(this).find('input[name="precio[]"]').val());

        total += cantidad * precio;
    });

    let total_impuesto = (total / 1.12) * 0.12;
    let totalCompra = total - total_impuesto;
    let total_pagar = totalCompra + total_impuesto;

    $("#totalCompra").html("Q." + totalCompra.toFixed(2));
    $("#total_impuesto_html").html("Q." + total_impuesto.toFixed(2));
    $("#total_pagar_html").html("Q." + total_pagar.toFixed(2));

    $("#total_impuesto").val(total_impuesto.toFixed(2));
    $("#total_pagar").val(total_pagar.toFixed(2));
}

function limpiarDetalleCompra() {
    $("#producto_id").val("");
    $("#cantidad").val("");
    $("#precio").val("");
}

function limpiarCompra() {
    cont = 0;
    total = 0;

    $("#detallesC").empty();

    $("#totalCompra").html("Q.0.00");
    $("#total_impuesto_html").html("Q.0.00");
    $("#total_pagar_html").html("Q.0.00");

    $("#total_impuesto").val("0.00");
    $("#total_pagar").val("0.00");

    $("#guardarC").hide();
    $("#guardarC").prop("disabled", false);
    $("#guardarC").text("Registrar");
}

function evaluarCompra() {
    if ($("#detallesC tr").length > 0) {
        $("#guardarC").show();
    } else {
        $("#guardarC").hide();
    }
}

function compraInsert() {
    let btn = $("#guardarC");

    if (btn.prop("disabled")) {
        return false;
    }

    if (!$("#proveedor_id").val()) {
        Swal.fire({
            icon: 'warning',
            title: 'Proveedor requerido',
            text: 'Debe seleccionar un proveedor antes de registrar la compra.'
        });
        return false;
    }

    if ($("#detallesC tr").length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Detalle requerido',
            text: 'Debe agregar al menos un producto a la compra.'
        });
        return false;
    }

    btn.prop("disabled", true);
    btn.text("Procesando...");

    let frm = document.getElementById('createForm');
    let form = new FormData(frm);

    $.ajax({
        type: "post",
        dataType: "json",
        headers: { 'X-CSRF-TOKEN': $('#_token').val() },
        url: "insertarCompra",
        data: form,
        contentType: false,
        processData: false,

        success: function (response) {
            if (response.error) {
                btn.prop("disabled", false);
                btn.text("Registrar");

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.mensaje
                });

            } else {
                Swal.fire({
                    icon: 'success',
                    title: response.mensaje,
                    confirmButtonText: 'Ok',
                    allowOutsideClick: false
                }).then(() => {
                    location.reload();
                });
            }
        },

        error: function (xhr, estado, error) {

            btn.prop("disabled", false);
            btn.text("Registrar");

            console.log(estado);
            console.log(error);

            let mensaje = 'No se pudo registrar la compra.';

            if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                mensaje = xhr.responseJSON.mensaje;
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: mensaje
            });
        }
    });

    return false;
}