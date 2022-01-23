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

function limpiarCard(){
    document.getElementById('report_asistencia').removeAttribute('href')
    document.getElementById('report_asistencia').removeAttribute('onclick')
    document.getElementById('report_calificacion').removeAttribute('href')
    document.getElementById('report_calificacion').removeAttribute('onclick')
}
