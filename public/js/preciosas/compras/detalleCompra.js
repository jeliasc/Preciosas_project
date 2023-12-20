function detalleCompra(id) {  
    $.ajax({
        url: "detalleCompra/"+id,
        method: 'GET',
        dataType: 'json',
        data: {id},
        success: function (response) {
            console.log(response.data);
        },
        error: function (error) {
            console.log(estado);
            console.log(error);        }
    });
    
}
