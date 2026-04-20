window.onload=function(){
    var fecha = new Date();
    var mes = fecha.getMonth()+1;
    var dia = fecha.getDate();
    var anio = fecha.getFullYear();
    if(dia<10) 
        dia = '0'+dia;
    if(mes<10)
        mes = '0'+mes;
    document.getElementById('fechaFinal').value = anio+'-'+mes+'-'+dia;
}