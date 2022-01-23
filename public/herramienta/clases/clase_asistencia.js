$(document).ready(function(){
    $("#input_asistencia").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_asistencia tr").filter(function(){$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)});
    });
});


function generarEstudiantesDate(){
    document.getElementById('table_asistencia').innerHTML = ""

    var periodo = document.getElementById('periodoestudio').value
    var idclass = document.getElementById('idasignacion').value
    var token = document.getElementById('tokenasignacion').value
    var fecha = document.getElementById('fecha_asistencia').value

    if(
        document.getElementById('idasignacion').value != ""  &&
        document.getElementById('tokenasignacion').value != "" &&
        document.getElementById('periodoestudio').value != "" &&
        document.getElementById('fecha_asistencia').value != ""
    ){
        fetch(`/clases/detail/estudiantes/${idclass}/${token}/periodo/${periodo}/${fecha}`)
        .then(response => response.json()).then(data => {
            document.getElementById('table_asistencia').innerHTML = ""
            console.log(data)

            if(data[0] == 200){
                var inasistencia = data.inasistencia; var alumnos = data.estudiantes;
                if(alumnos.length > 0){
                    var conteo = 1;
                    alumnos.forEach(element => {
                        generateListado(element , inasistencia , conteo);
                        conteo++;
                    });
                }else{
                   document.getElementById('table_asistencia').innerHTML = ""
                }
            }else{
                document.getElementById('table_asistencia').innerHTML = ""
            }
        })
        .catch(error => console.error('MOSTRANDO ERROR GENERAR ESTUDIANTES: '+error));
    }
}

function generateListado(element, inasis, conteo) {
    // console.log(element, inasis)

    var tr_general = document.createElement('tr');
        tr_general.setAttribute('class','td text-name-estu tr_cont')

        var pn = (element.primer_nombre == null || element.primer_nombre == "" ? '': ((element.primer_nombre).toString()).toUpperCase());
        var sn = (element.segundo_nombre == null || element.segundo_nombre == "" ? '': ((element.segundo_nombre).toString()).toUpperCase());
        var pa = (element.apellido_paterno == null || element.apellido_paterno == "" ? '': ((element.apellido_paterno).toString()).toUpperCase());
        var sa = (element.apellido_materno == null || element.apellido_materno == "" ? '': ((element.apellido_materno).toString()).toUpperCase());

        var td_estudiante = document.createElement('td');
            td_estudiante.style.fontSize = "12px";
            td_estudiante.style.padding = "8px";
            td_estudiante.innerText = (conteo+'.-'+pn +' '+ sn +' '+ pa +' '+ sa)

        var td_inas = document.createElement('td');
            td_inas.style.width = "8%"
            var inpcheck = document.createElement('input')
                inpcheck.setAttribute('type', 'checkbox')
                inpcheck.setAttribute('name','inasig[]')
                inpcheck.setAttribute('class', 'form-control')
                inpcheck.setAttribute('id','checkbox_'+element.id)
                inpcheck.style.filter = "hue-rotate(160deg)";
                inpcheck.style.marginRight = "17px"
                inpcheck.style.fontSize = "3px"
                td_inas.append(inpcheck)

        var td_justificar = document.createElement('td');
            td_justificar.style.width = "5%"

            var bntjustificar = document.createElement('button')
                bntjustificar.setAttribute('type','button')
                bntjustificar.setAttribute('class','btn btn-sm btn-info')
                bntjustificar.setAttribute('title','justificar falta');
                bntjustificar.innerHTML = `<i class="ti-stamp"></i>`;

            var iconjustificado = document.createElement('i');
                iconjustificado.setAttribute('class','ti-check-box text-success');
                iconjustificado.setAttribute('title','falta justificada');

                inasis.forEach(asign => {
                    if(asign.estudiante_fk == element.id){

                        inpcheck.setAttribute('disabled', true);
                        bntjustificar.setAttribute('id','btnjust_'+asign.id)

                        if(asign.asistencia == 0){
                            inpcheck.setAttribute('checked', true);
                            //JUSTIFICADO
                            if(asign.asistencia_justificada == 1){
                                td_justificar.append(iconjustificado);
                            }
                            //JUSTIFICAR
                            if(asign.asistencia_justificada == null){
                                bntjustificar.setAttribute('onclick',`justificarFalta(${asign.id})`)
                                td_justificar.append(bntjustificar);
                            }
                        }
                    }
                });

    tr_general.append(td_estudiante)
    tr_general.append(td_inas)
    tr_general.append(td_justificar)
    document.getElementById('table_asistencia').append(tr_general)
}

function validateForm(params){
    var n = document.getElementById(params).value
    if(n == ""){
        return "error"
    }else{
        return "success"
    }
}
document.querySelector('#btnSaveAsistencia').addEventListener('click', ()=> {
    var periodo = validateForm('periodoestudio');
    var fecha = validateForm('fecha_asistencia');

    if(periodo == "success" && fecha == "success"){
        $("#btnSaveAsistencia").attr("disabled", true).text("").append(spinner);

        var asistencias = new Array();
        $('input:checkbox[name="inasig[]"]').each(function() {
            var spli = ($(this).prop("id")).split('_')
            var check = 1;
            if($(this).prop("checked") == true){ check = 0 ; }

            asistencias.push({
                'id_estudiante' : spli[1],
                'asistencia': check
            });
        });

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("clase_fk", document.getElementById('idasignacion').value);
        formData.append("tokenclase_fk", document.getElementById('tokenasignacion').value);
        formData.append("parcial", document.getElementById('periodoestudio').value);
        formData.append("fecha", document.getElementById("fecha_asistencia").value );
        formData.append("estudiantes", JSON.stringify(asistencias));

        fetch('/clases/asistencia/post', {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            if (data) {
                if (data.success) {
                    generarEstudiantesDate();
                    toastr.success(data.success);
                }
                if (data.error) { toastr.error(data.error); }
                if (data.warning) {toastr.warning(data.warning); }
            }
        })
        .catch((error) => { console.error(error); })
        .finally(() => {
            $("#btnSaveAsistencia").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Guardar`);
        });
    }else{
        if(validateForm('periodoestudio') == "error" && validateForm('fecha_asistencia') == "error"){
            toastr.warning('Por favor seleccionar parcial y fecha de asistencia');
        }else{
            if(validateForm('fecha_asistencia') == "error"){
                toastr.warning('Por favor seleccionar una fecha de asistencia');
            }
            if(validateForm('periodoestudio') == "error" ){
                toastr.warning('Por favor seleccionar un parcial');
            }
        }
    }
});
function justificarFalta(idregistro) {
    if(confirm('Esta seguro de justificar la falta?')){
        $("#btnjust_"+idregistro).attr("disabled", true).text("").append(spinner);
        console.log(idregistro);
        fetch('/clases/asistencia/justificar/'+idregistro)
        .then(response => response.json()).then(data => {
            if(data) {
                if(data.success) {
                    generarEstudiantesDate();
                    toastr.success(data.success);
                }
                if (data.error){
                    toastr.error(data.error);
                    $("#btnjust_"+idregistro).attr("disabled", false).text("").append(`<i class="ti-stamp"></i>`);
                }
                if (data.warning) {toastr.warning(data.warning); }
            }
        })
        .catch(error => {
            console.log('MOSTRANDO ERROR JUSTIFICAR FALTA: '+error);
            $("#btnjust_"+idregistro).attr("disabled", false).text("").append(`<i class="ti-stamp"></i>`);
        })
    }
}
