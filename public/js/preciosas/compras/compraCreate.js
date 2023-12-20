$(document).ready(function () {
    $("#agregarC").click(function () { 
        agregarC();
    });
});

function compraCreate() {
    $.ajax({
        type: "get",
        dataType: "json",
        url: "crearCompra",

        success: function (response) {
            if(response.error){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: response.mensaje,
                    footer: ''
                  });  

            }else{
                let proveedores = response.proveedores;
                let productos = response.productos;
                let textoProd = '';
                let textoProv = '';     
                
                console.log(proveedores);
                console.log(productos);

                $("#proveedor_id" ).empty();
                textoProv += '<option value="">Elija una opcion</option>';
                
                $("#producto_id" ).empty();
                textoProd += '<option value="">Elija una opcion</option>';  

                proveedores.forEach(proveedor=> {
                    if(proveedor.id != ''){
                        textoProv += '<option value="'+proveedor.id+'">'+proveedor.nombre+'</option>';
                    }
                });

                productos.forEach(producto => {
                    if(producto.id != ''){
                        textoProd += '<option value="'+producto.id+'">'+producto.nombre+'</option>';
                    }
                });

                $("#proveedor_id" ).append(textoProv);
                $("#producto_id" ).append(textoProd);
                $('#createModal').modal('show');  
            }
        },error: function (jqXHR, estado, error){
            console.log(estado);
            console.log(error);
            $('#createModal').modal('show');   
        }
    })
}

var cont=0;
totalCompra=0;
total=0;
subtotal=[];

$("#guardarC").hide();

function agregarC(){
    producto_id=$("#producto_id").val();
    producto=$("#producto_id option:selected").text();
    cantidad=$("#cantidad").val();
    precio=$("#precio").val();
    impuesto=$("#tax").val();

    if(producto_id != "" && cantidad != "" && cantidad > 0 && precio != "" ){
        subtotal[cont]=cantidad*precio;
        total=total+subtotal[cont];
        var fila = '<tr class="selected" id="fila' + cont + '"><td><button type="button" class="btn btn-danger btn-sm" onclick="eliminar(' + cont + ');"><i class="fa fa-times"></i></button></td><td><input type="hidden" name="producto_id[]" value="' + producto_id + '">' + producto + '</td><td><input type="hidden" id="precio[]" name="precio[]" value="' + precio + '"> <input class="form-control" type="number" id="precio[]" value="' + precio +'" disabled> </td><td><input type="hidden" name="cantidad[]" value="' + cantidad + '"><input class="form-control" type="number" value="' + cantidad + '" disabled></td><td align="right">s/t Q. ' + subtotal[cont] + '</td></tr>'; 
        cont++;
        limpiar();
        totales();
        evaluar();
        $('#detallesC').append(fila);
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Your work has been saved',
            showConfirmButton: false,
            timer: 1500
          })
    }else{
        Swal.fire('Debe llenar todos los campos')
    }
}

function limpiar(){
    $("#producto_id").val("");
    $("#cantidad").val("");
    $("#precio").val("");
}

function totales(){      
    total_impuesto= (total/1.12)*(0.12);
    totalCompra= total - total_impuesto;
    total_pagar=totalCompra + total_impuesto;
    $("#totalCompra").html("Q." + totalCompra.toFixed(2));
    $("#total_impuesto_html").html("Q." + total_impuesto.toFixed(2));
    $("#total_pagar_html").html("Q." + total_pagar.toFixed(2));
    $("#total_impuesto").val(total_impuesto.toFixed(2));
    $("#total_pagar").val(total_pagar.toFixed(2));   
}

function evaluar(){
    if(total > 0){
        $("#guardarC").show();
    }else{
        $("#guardarC").hide();
    }
}

function eliminar(index){
    total= total - subtotal[index];
    total_impuesto= total * impuesto/100;
    totalCompra= total - total_impuesto;
    total_pagar_html= totalCompra + total_impuesto;
    $("#totalCompra").html("Q." + totalCompra);
    $("#total_impuesto_html").html("Q." + total_impuesto);
    $("#total_pagar_html").html("Q." + total_pagar_html);
    $("#total_impuesto").val(total_impuesto.toFixed(2));
    $("#total_pagar").val(total_pagar_html.toFixed(2));
    $("#fila" + index).remove();
    evaluar();
}

function compraInsert() {
    let frm = document.getElementById('createForm');
    var form = new FormData(frm);

    $.ajax({
        type: "post",
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('#_token').val()},
        url: "insertarCompra",
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
 