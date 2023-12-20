$(document).ready(function () {
    $("#agregarV").click(function () { 
        agregarV();
    });
});

var cont=1;
totalVenta=0;
total=0;
subtotal=[];

$("#guardarV").hide();
$("#producto_id").change(mostrarValores);

function mostrarValores(){
    datosProducto = document.getElementById('producto_id').value.split('_');
    $("#precio").val(datosProducto[2]);
    $("#stock").val(datosProducto[1]);
}

function agregarV(){
    datosProducto = document.getElementById('producto_id').value.split('_');
    producto_id=datosProducto[0];
    producto=$("#producto_id option:selected").text();
    cantidad=parseInt($("#cantidad").val());
    extra=parseFloat($("#extra").val());
    descuento=parseFloat($("#descuento").val());
    precio=parseFloat($("#precio").val());
    impuesto=$("#tax").val();
    stock=parseInt($("#stock").val());
    comentario=$("#comentario").val();

    if(producto_id != "" && cantidad != "" && cantidad > 0 && precio != ""){
        if(stock >= cantidad){
            subtotal[cont]=(((precio*cantidad)+extra)-(((precio*cantidad)+extra)*(descuento/100)));
            descuento=(((precio*cantidad)+extra)*(descuento/100));
            total=total+subtotal[cont];
            var fila = '';
            fila += '<tr class="selected" id="fila' + cont + '">';
                fila += '<td> <button type="button" class="btn btn-danger btn-sm" onclick="eliminar(' + cont + ');"> <i class="fa fa-times fa-2x"></i></button></td>';
                fila += '<td><input type="hidden" name="cantidad[]" value="' + cantidad + '"><input class="form-control" type="number" value="' + cantidad + '" disabled></td>';
                fila += '<td><input type="hidden" name="producto_id[]" value="' + producto_id + '">' + producto + '</td>';
                fila += '<td><input type="hidden" name="precio[]" value="' + parseFloat(precio).toFixed(2) + '"><input class="form-control" type="number" value="' + parseFloat(precio).toFixed(2) +'" disabled></td>';
                fila += '<td><input type="hidden" name="extra[]" value="' + parseFloat(extra) + '"><input class="form-control" type="number" value="' + parseFloat(extra) +'" disabled></td>';
                fila += '<td><input type="hidden" name="descuento[]" value="' + parseFloat(descuento) + '"><input class="form-control" type="number" value="' + parseFloat(descuento) +'" disabled></td>';
                fila += '<td><input type="hidden" name="comentario[]" value="' + comentario + '"><input class="form-control" type="text" value="' + comentario +'" ></td><td align="right">Q.' + parseFloat(subtotal[cont]).toFixed(2) + '</td>';
            fila += '</tr>';
            cont++;
            limpiar();
            totales();
            evaluar();
            $('#detallesV').append(fila);
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
              })
        }else{
            Swal.fire('Revise su stock')
            }
    }else{
        Swal.fire('Debe llenar los campos requeridos')
    }
}

function limpiar(){
    $("#cantidad").val("");
    $("#comentario").val("---------------");
    $("#descuento").val("0");
    $("#extra").val("0");
    $("#producto_id").val("");
    $("#precio").val("");
}

function totales(){      
    total_impuesto= (total/1.12)*(0.12);
    totalVenta= total - total_impuesto;
    total_pagar=totalVenta + total_impuesto;
    $("#totalVenta").html("Q." + totalVenta.toFixed(2));
    $("#total_impuesto_html").html("Q." + total_impuesto.toFixed(2));
    $("#total_pagar_html").html("Q." + total_pagar.toFixed(2));
    $("#total_impuesto").val(total_impuesto.toFixed(2));
    $("#total_pagar").val(total_pagar.toFixed(2));   
}

function evaluar(){
    if(total > 0){
        $("#guardarV").show();
    }else{
        $("#guardarV").hide();
    }
}

function eliminar(index){
    total= total - subtotal[index];
    total_impuesto= (total/1.12)*(0.12);
    totalVenta= total - total_impuesto;
    total_pagar=totalVenta + total_impuesto;
    $("#totalVenta").html("Q." + totalVenta.toFixed(2));
    $("#total_impuesto_html").html("Q." + total_impuesto.toFixed(2));
    $("#total_pagar_html").html("Q." + total_pagar.toFixed(2));
    $("#total_impuesto").val(total_impuesto.toFixed(2));
    $("#total_pagar").val(total_pagar.toFixed(2));   
    $("#fila" + index).remove();
    evaluar();
}

function ventaInsert() {
    let frm = document.getElementById('createForm');
    var form = new FormData(frm);

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "insertarVenta",
        data: form,
        contentType: false,
        processData: false,   

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
 