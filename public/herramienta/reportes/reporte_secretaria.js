function searchAsignaturesCurso(identificador){
    limpiarCard()
    document.getElementById('select_asignatura').setAttribute('disabled', true)
    $('#select_asignatura').empty();

    document.getElementById('select_estudiantes').setAttribute('disabled', true)
    $('#select_estudiantes').empty();

    document.getElementById('alert_danger_solicitud').style.display = "none";
    var receptor = identificador;
    if(receptor != ""){
        var condicion = new Array();
        fetch('/reportes/buscar/asignaturas/estudiantes/curso/'+identificador)
        .then(response => response.json()).then(data => {
            document.getElementById('alert_danger_solicitud').style.display = "none";
            if (data) {
                if (data.success){condicion.push('success', data[0], data[1]);}
                if (data.error){condicion.push('error', data.error);}
                if (data.warning){ condicion.push('error', data.warning);}
            }
        })
        .catch(error => console.error(error))
        .finally(()=>{
            if(condicion[0] == "success"){
                document.getElementById('alert_danger_solicitud').style.display = "none";
                document.getElementById('msj_danger').innerHTML = "";
                llenadoAsignaturas(condicion[1])
                llenadoAlumnos(condicion[2])
            }
            if(condicion[0] == "error"){
                document.getElementById('alert_danger_solicitud').style.display = "block";
                document.getElementById('msj_danger').innerHTML = condicion[1];
                setTimeout(()=>{document.getElementById('alert_danger_solicitud').style.display = "none" }, 3000);
            }
        })
    }
}
function llenadoAsignaturas(asignaturas) {
    var datosSelect = new Array();
        datosSelect.push({ id : '--', text: '-- Seleccionar --'})
        //datosSelect.push({ id : 'ALL', text: 'Todas'})
        asignaturas.forEach(element => {
            datosSelect.push({
                id: element.clase_id+'-'+element.clase_token,
                text: (element.nombre +' || Docente: '+element.usuario_papellido +' '+ element.usuario_sapellido  +' '+ element.usuario_pnombre +' '+ element.usuario_snombre )
            })
        });
        $("#select_asignatura").select2().empty();
        if(datosSelect.length > 0){
            document.getElementById('select_asignatura').removeAttribute('disabled')
            $("#select_asignatura").select2({ data: datosSelect });
            $('#select_asignatura').val('--').trigger('change');
        }
}
function llenadoAlumnos(alumnos) {
    var datosSelect = new Array();
        datosSelect.push({ id : '--', text: '-- Seleccionar --'})
        datosSelect.push({ id : 'ALL', text: 'General'})
        alumnos.forEach(element => {
            datosSelect.push({
                id: element.id,
                text: (element.apellido_paterno +' '+ element.apellido_materno  +' '+ element.primer_nombre +' '+ element.segundo_nombre  )
            })
        });

        $("#select_estudiantes").select2().empty();
        if(datosSelect.length > 0){
            document.getElementById('select_estudiantes').removeAttribute('disabled')
            $("#select_estudiantes").select2({ data: datosSelect });
            $('#select_estudiantes').val('--').trigger('change');
        }
}

function searchEstudiantesPorClase() {
    limpiarCard()
    var clase = document.getElementById('select_asignatura').value
    var alumn = document.getElementById('select_estudiantes').value

    if(clase != null && clase != "" && clase != "--" && alumn != null && alumn != "" && alumn != "--"){
        document.getElementById('cont_report').style.display = "block"

        var porcion = (document.getElementById('select_asignatura').value).split('-');

        document.getElementById('report_asistencia').setAttribute('href', `/reporte/asistencia/clase/${porcion[0]}/${porcion[1]}/${alumn}`);
        document.getElementById('report_asistencia').setAttribute('onclick', "window.open(this.href,'window','width=1275, height=775');return false");
        
        if (porcion[0] == 'ALL') {
            document.getElementById('report_calificacion').setAttribute('href', `/reportes/buscar/calificaciones-asignatura/clase/${alumn}/${porcion[0]}`);
            document.getElementById('report_calificacion').setAttribute('onclick', "window.open(this.href,'window','width=1275, height=775');return false");
        }else{
            document.getElementById('report_calificacion').setAttribute('href', `/reporte/calificaciones/clase/${porcion[0]}/${porcion[1]}/${alumn}`);
            document.getElementById('report_calificacion').setAttribute('onclick', "window.open(this.href,'window','width=1275, height=775');return false");
        }
        //document.getElementById('report_calificacion').setAttribute('href', `/reporte/calificaciones/clase/${porcion[0]}/${porcion[1]}/${alumn}`);
        //reportes/buscar/calificaciones-asignatura/clase/{clase}/{alumn}
        //document.getElementById('report_calificacion').setAttribute('href', `/reportes/buscar/calificaciones-asignatura/clase/${porcion[0]}/${alumn}`);
        //document.getElementById('report_calificacion').setAttribute('onclick', "window.open(this.href,'window','width=1275, height=775');return false");
    }
}
function limpiarCard(){
    document.getElementById('cont_report').style.display = "none"
    document.getElementById('report_asistencia').removeAttribute('href')
    document.getElementById('report_asistencia').removeAttribute('onclick')
    document.getElementById('report_calificacion').removeAttribute('href')
    document.getElementById('report_calificacion').removeAttribute('onclick')
}
$(document).ready(function() {
    $('.select-destin').select2();
    document.getElementById('select_curso').removeAttribute('disabled');
});
