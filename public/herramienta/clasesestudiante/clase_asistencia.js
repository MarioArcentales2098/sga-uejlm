$(document).ready(function(){
    $("#input_asistencia").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_asistencia tr").filter(function(){$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)});
    });
});


function generarEstudiantesDate(){
    document.getElementById('table_asistencia').innerHTML = ""
    document.getElementById('contenedor_boton_asistencia').innerHTML = ""
    var idclass = document.getElementById('idasignacion').value
    var token = document.getElementById('tokenasignacion').value
    var periodo = document.getElementById('periodoestudio').value

    if(
        document.getElementById('idasignacion').value != ""  &&
        document.getElementById('tokenasignacion').value != "" &&
        (document.getElementById('periodoestudio').value != "" || document.getElementById('periodoestudio').value != "--")
    ){
        fetch(`/clases/estudiante/detail/estudiantes/${idclass}/${token}/periodo/${periodo}`)
        .then(response => response.json()).then(data => {
            document.getElementById('table_asistencia').innerHTML = ""
            document.getElementById('contenedor_boton_asistencia').innerHTML = ""
            if(data[0] == 200){
                var inasistencia = data.inasistencia; var alumnos = data.estudiantes;
                if(inasistencia.length > 0){
                    // var enlace = `/reporte/asistencia/clase/${idclass}/${token}/${data.alumn}`;
                    var enlace = `/reporte/asistencia/clase/estudiante/${idclass}/${token}/${periodo}`;

                    document.getElementById('contenedor_boton_asistencia').innerHTML = `
                        <div class="d-flex">
                            <div>
                                <a type="button" class="btn btn-sm btn-primary" href="${enlace}" onclick="window.open(this.href,'window','width=1275, height=775'); return false" style="margin-top: 30px;"><i class="ti-printer"></i> PDF Asistencias</a>
                            </div>
                        </div>
                    `;
                    inasistencia.forEach(element => {
                        var tr_general = document.createElement('tr');
                            tr_general.setAttribute('class','td text-name-estu tr_cont')
                            var td_estudiante = document.createElement('td');
                                td_estudiante.style.fontSize = "12px";
                                td_estudiante.style.padding = "8px";
                                td_estudiante.innerText = element.fecha
                            tr_general.append(td_estudiante)

                            var td_inas = document.createElement('td');
                                td_inas.style.width = "8%"
                                var icono = document.createElement('i');
                                if (element.asistencia == 1) {
                                    // icono.setAttribute('class', 'text-success')
                                    icono.style.color = "green"
                                } else {
                                    // icono.setAttribute('class', 'text-danger')
                                    icono.style.color = "red"
                                }
                                if (element.asistencia == 1) {
                                    icono.setAttribute('class', 'fa fa-check')
                                } else {
                                    if (element.asistencia_justificada == 1) {
                                        icono.setAttribute('class', 'fa fa-check')
                                    } else {
                                        icono.setAttribute('class', 'fa fa-times')
                                    }
                                }
                                td_inas.append(icono)
                            tr_general.append(td_inas)
                        document.getElementById('table_asistencia').append(tr_general)
                    });
                }else{
                   document.getElementById('table_asistencia').innerHTML = "<tr><td>No se ha tomado ninguna asistencia aun.</td></tr>"
                }
            }else{
                document.getElementById('table_asistencia').innerHTML = "Error"
            }
        })
        .catch(error => console.error('MOSTRANDO ERROR GENERAR ESTUDIANTES: '+error));
    }
}
