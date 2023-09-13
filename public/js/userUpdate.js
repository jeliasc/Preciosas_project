$(document).ready(function () {
    mostrarUsuario()
});
function mostrarUsuario(){
    $.ajax({
        url: "{{ route('editarUsuario') }}",
        method: 'get',
        success: function(dato) {
            var usuario = json.parse(dato.trim());
            console.log(usuario);
            
        },
        error: function() {
          console.log("No se ha podido obtener la información");
        }
    
    
    });
}



