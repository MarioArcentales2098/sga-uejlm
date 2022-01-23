function validateReport(params) {
    document.getElementById('cont_report').style.display = "none";
    limpiarCard();

    var n = document.getElementById(params).value
    console.log(n)
    if(n == ""){
        document.getElementById(params+'-has-error').classList.add('has-error');
        return "error";
    }else{
        document.getElementById(params+'-has-error').classList.remove('has-error');
        return "success";
    }
}
document.querySelector('#btnGenerarReportes').addEventListener('click', ()=>{
    var estu = validateReport('select_estu_report');

    limpiarCard()
   
    if(estu == "success"){
        $('#btnGenerarReportes').attr('disabled', true).text('').append(spinner);
        document.getElementById('cont_report').style.display = "none"

        var slect = document.getElementById('select_estu_report').value
        var clase_fk = document.getElementById('idasignacion').value
        var token = document.getElementById('tokenasignacion').value

        document.getElementById('report_asistencia').setAttribute('href', `/clases/${clase_fk}/token/${token}/generar/reporte/asistencias/${slect}`)
        document.getElementById('report_asistencia').setAttribute('onclick', "window.open(this.href,'window','width=1275, height=775');return false")


        document.getElementById('report_calificacion').setAttribute('href', `/clases/${clase_fk}/token/${token}/generar/reporte/calificaciones/${slect}`)
        document.getElementById('report_calificacion').setAttribute('onclick', "window.open(this.href,'window','width=1275, height=775');return false")
        
        setTimeout(function(){ 
            $('#btnGenerarReportes').attr('disabled', false).text('').append(`<i class="ti-filter"></i> Generar`);
            document.getElementById('cont_report').style.display = "block"
        }, 1000);
    }
});
function limpiarCard(){
    document.getElementById('report_asistencia').removeAttribute('href')
    document.getElementById('report_asistencia').removeAttribute('onclick')
    document.getElementById('report_calificacion').removeAttribute('href')
    document.getElementById('report_calificacion').removeAttribute('onclick')
}