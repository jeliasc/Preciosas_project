$(document).ready(function () {
    $("#agregarV").click(function () {
        agregarV();
    });
});

var cont = 1;
var total = 0;
var productosVenta = {};

$("#guardarV").hide();
$("#producto_id").change(mostrarValores);

function mostrarValores() {
    let datosProducto = document.getElementById('producto_id').value.split('_');

    $("#precio").val(datosProducto[2]);
    $("#stock").val(datosProducto[1]);
}

function agregarV() {
    let datosProducto = document.getElementById('producto_id').value.split('_');

    let producto_id = datosProducto[0];
    let stock = parseInt(datosProducto[1]);
    let precio = parseFloat(datosProducto[2]);

    let producto = $("#producto_id option:selected").text();
    let cantidad = parseInt($("#cantidad").val());
    let extra = parseFloat($("#extra").val()) || 0;
    let descuentoPorcentaje = parseFloat($("#descuento").val()) || 0;
    let comentario = $("#comentario").val();

    if (producto_id === "" || isNaN(cantidad) || cantidad <= 0 || isNaN(precio)) {
        Swal.fire('Debe llenar los campos requeridos');
        return;
    }

    if (!productosVenta[producto_id]) {
        if (cantidad > stock) {
            Swal.fire('Revise su stock');
            return;
        }

        productosVenta[producto_id] = {
            index: cont,
            producto_id: producto_id,
            producto: producto,
            cantidad: cantidad,
            stock: stock,
            precio: precio,
            extra: extra,
            descuentoPorcentaje: descuentoPorcentaje,
            comentario: comentario
        };

        crearFila(productosVenta[producto_id]);
        cont++;

    } else {
        let item = productosVenta[producto_id];
        let nuevaCantidad = item.cantidad + cantidad;

        if (nuevaCantidad > item.stock) {
            Swal.fire('No hay stock suficiente para agregar más unidades');
            return;
        }

        item.cantidad = nuevaCantidad;
        item.extra += extra;
        item.comentario = comentario;

        actualizarFila(producto_id);
    }

    limpiar();
    recalcularTotales();
    evaluar();

    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: 'Producto agregado',
        showConfirmButton: false,
        timer: 1200
    });
}

function crearFila(item) {
    let subtotal = calcularSubtotal(item);
    let descuentoMonto = calcularDescuentoMonto(item);

    let fila = '';

    fila += '<tr class="selected" id="fila' + item.producto_id + '">';

    fila += '<td>';
    fila += '<button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(\'' + item.producto_id + '\');">';
    fila += '<i class="fa fa-times fa-2x"></i>';
    fila += '</button>';
    fila += '</td>';

    fila += '<td>';
    fila += '<div class="input-group">';
    fila += '<button type="button" class="btn btn-secondary btn-sm" onclick="disminuirCantidad(\'' + item.producto_id + '\')">-</button>';
    fila += '<input type="hidden" name="cantidad[]" id="cantidad_hidden_' + item.producto_id + '" value="' + item.cantidad + '">';
    fila += '<input class="form-control text-center" type="number" id="cantidad_visible_' + item.producto_id + '" value="' + item.cantidad + '" disabled>';
    fila += '<button type="button" class="btn btn-secondary btn-sm" onclick="aumentarCantidad(\'' + item.producto_id + '\')">+</button>';
    fila += '</div>';
    fila += '</td>';

    fila += '<td>';
    fila += '<input type="hidden" name="producto_id[]" value="' + item.producto_id + '">';
    fila += item.producto;
    fila += '</td>';

    fila += '<td>';
    fila += '<input type="hidden" name="precio[]" value="' + item.precio.toFixed(2) + '">';
    fila += '<input class="form-control" type="number" value="' + item.precio.toFixed(2) + '" disabled>';
    fila += '</td>';

    fila += '<td>';
    fila += '<input type="hidden" name="extra[]" id="extra_hidden_' + item.producto_id + '" value="' + item.extra.toFixed(2) + '">';
    fila += '<input class="form-control" type="number" id="extra_visible_' + item.producto_id + '" value="' + item.extra.toFixed(2) + '" disabled>';
    fila += '</td>';

    fila += '<td>';
    fila += '<input type="hidden" name="descuento[]" id="descuento_hidden_' + item.producto_id + '" value="' + descuentoMonto.toFixed(2) + '">';
    fila += '<input class="form-control" type="number" id="descuento_visible_' + item.producto_id + '" value="' + descuentoMonto.toFixed(2) + '" disabled>';
    fila += '</td>';

    fila += '<td>';
    fila += '<input class="form-control" type="text" name="comentario[]" id="comentario_' + item.producto_id + '" value="' + item.comentario + '">';
    fila += '</td>';

    fila += '<td align="right" id="subtotal_' + item.producto_id + '">';
    fila += 'Q.' + subtotal.toFixed(2);
    fila += '</td>';

    fila += '</tr>';

    $('#detallesV').append(fila);
}

function actualizarFila(producto_id) {
    let item = productosVenta[producto_id];
    let subtotal = calcularSubtotal(item);
    let descuentoMonto = calcularDescuentoMonto(item);

    $("#cantidad_hidden_" + producto_id).val(item.cantidad);
    $("#cantidad_visible_" + producto_id).val(item.cantidad);

    $("#extra_hidden_" + producto_id).val(item.extra.toFixed(2));
    $("#extra_visible_" + producto_id).val(item.extra.toFixed(2));

    $("#descuento_hidden_" + producto_id).val(descuentoMonto.toFixed(2));
    $("#descuento_visible_" + producto_id).val(descuentoMonto.toFixed(2));

    $("#subtotal_" + producto_id).html("Q." + subtotal.toFixed(2));
}

function aumentarCantidad(producto_id) {
    let item = productosVenta[producto_id];

    if (item.cantidad + 1 > item.stock) {
        Swal.fire('No hay stock suficiente');
        return;
    }

    item.cantidad++;
    actualizarFila(producto_id);
    recalcularTotales();
    evaluar();
}

function disminuirCantidad(producto_id) {
    let item = productosVenta[producto_id];

    item.cantidad--;

    if (item.cantidad <= 0) {
        eliminarProducto(producto_id);
        return;
    }

    actualizarFila(producto_id);
    recalcularTotales();
    evaluar();
}

function eliminarProducto(producto_id) {
    delete productosVenta[producto_id];
    $("#fila" + producto_id).remove();

    recalcularTotales();
    evaluar();
}

function calcularSubtotal(item) {
    let bruto = (item.precio * item.cantidad) + item.extra;
    let descuento = bruto * (item.descuentoPorcentaje / 100);

    return bruto - descuento;
}

function calcularDescuentoMonto(item) {
    let bruto = (item.precio * item.cantidad) + item.extra;

    return bruto * (item.descuentoPorcentaje / 100);
}

function recalcularTotales() {
    total = 0;

    Object.keys(productosVenta).forEach(function (producto_id) {
        total += calcularSubtotal(productosVenta[producto_id]);
    });

    let total_impuesto = (total / 1.12) * 0.12;
    let totalVenta = total - total_impuesto;
    let total_pagar = totalVenta + total_impuesto;

    $("#totalVenta").html("Q." + totalVenta.toFixed(2));
    $("#total_impuesto_html").html("Q." + total_impuesto.toFixed(2));
    $("#total_pagar_html").html("Q." + total_pagar.toFixed(2));

    $("#total_impuesto").val(total_impuesto.toFixed(2));
    $("#total_pagar").val(total_pagar.toFixed(2));
}

function limpiar() {
    $("#cantidad").val("");
    $("#comentario").val("---------------");
    $("#descuento").val("0");
    $("#extra").val("0");
    $("#producto_id").val("");
    $("#precio").val("");
    $("#stock").val("");
}

function evaluar() {
    if (Object.keys(productosVenta).length > 0) {
        $("#guardarV").show();
    } else {
        $("#guardarV").hide();
    }
}

function ventaInsert() {

    if (!$("#cliente_id").val()) {
        Swal.fire({
            icon: 'warning',
            title: 'Cliente requerido',
            text: 'Debe seleccionar un cliente antes de registrar la venta.'
        });
        return false;
    }
    
    let btn = $("#guardarV");

    // Evita doble clic
    if (btn.prop("disabled")) {
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
        url: "insertarVenta",
        data: form,
        contentType: false,
        processData: false,

        success: function (response) {

            if (response.error) {

                // Reactiva botón si hubo error
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

        error: function (xhr) {

            // Reactiva botón si falla AJAX
            btn.prop("disabled", false);
            btn.text("Registrar");

            let mensaje = 'Error en la comunicación con el servidor';

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