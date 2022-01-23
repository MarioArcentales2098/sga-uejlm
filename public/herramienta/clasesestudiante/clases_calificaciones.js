$(document).ready(function(){
    $("#input_calificaciones").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_calificaciones tr").filter(function(){$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)});
    });
});

function getOptionSelectedById(identificador) {
    var select = document.getElementById(identificador);

    var seleccionado = "";
    if (select.options.length > 0) {
        seleccionado = select.options[select.selectedIndex];
    }
    return seleccionado
}

function seleccionacionar_contenido() {
    var option_elemento = getOptionSelectedById("periodoestudio_calificacion").dataset.option;
    if (option_elemento != undefined) {
        document.getElementById('table_calificaciones').innerHTML = ""
        document.getElementById('contenedor_boton_nuevo').innerHTML = ""

        if (option_elemento == "Q") {
            generarEstudiantesCalificacionesQuimestre();
        }
        if (option_elemento == "P") {
            generarEstudiantesCalificaciones();
        }
    }
}

function actualizarCalificacionXLineaQuimestre(identificador) {
    var data_group = document.getElementsByClassName("quimestre_est_"+identificador);

    var parcial = 8;
    var restante = 2;

    var sum_paricla = 0.00;
    var sum_examen = 0.00;

    var n_parcial = new Array();
    var n_examen = new Array();

    for (i = 0; i < data_group.length; i++) {
        var calificacion = 0;
        if (data_group[i].dataset.type_note == "nota_parcial") { calificacion = data_group[i].innerText; n_parcial.push(calificacion);}
        if (data_group[i].dataset.type_note == "nota_examen") { calificacion = data_group[i].value; n_examen.push(calificacion);}
    }

    n_parcial.forEach(element => { sum_paricla += parseFloat(element);});
    n_examen.forEach(element => { sum_examen += parseFloat(element);});

    var f_parcial = n_parcial.length > 0 ? ((sum_paricla / n_parcial.length) * parcial / 10) : sum_paricla;
    var f_examen = n_examen.length > 0 ? ((sum_examen / n_examen.length) * restante / 10) : sum_examen;

    document.getElementById("quimestre_total_"+identificador).innerHTML = parseFloat(f_parcial + f_examen).toFixed(2);
}

function generarEstudiantesCalificacionesQuimestre(){
    var periodo = document.getElementById('periodoestudio_calificacion').value
    var idclass = document.getElementById('idasignacion').value
    var token = document.getElementById('tokenasignacion').value
    var option_elemento = getOptionSelectedById('periodoestudio_calificacion').dataset.option;

    if(
        option_elemento == "Q" &&
        document.getElementById('idasignacion').value != ""  &&
        document.getElementById('tokenasignacion').value != "" &&
        document.getElementById('periodoestudio_calificacion').value != ""
    ){
        fetch(`/clases/estudiante/detail/estudiantes-calificaciones-quimestres/${idclass}/${token}/periodo/${periodo}`)
        .then(response => response.json()).then(data => {
            document.getElementById('table_calificaciones').innerHTML = ""
            if(data[0] == 200){
                var alumnos = data.estudiantes;
                var quimestres = data.quimestres;
                var parciales = data.parciales;
                var calificaciones = data.calificaciones;
                var calificaciones_examen = data.calificaciones_examen;

                var elemento_cantidad = 2;
                // var ruta = `/clases/${idclass}/token/${(token).toString()}/generar/reporte/calificaciones`;
                var ruta = `/reporte/calificaciones/clase/${idclass}/${(token).toString()}/${data.ident}`;
                document.getElementById('contenedor_boton_nuevo').innerHTML = `
                    <div class="d-flex">
                        <div>
                            <a type="button" class="btn btn-sm btn-danger" href="${ruta}" onclick="window.open(this.href,'window','width=1275, height=775'); return false" style="margin-top: 30px;" id="btnGenerarReportes"><i class="ti-printer"></i> PDF General</a>
                        </div>
                    </div>
                `;
                    var conteo = 1;

                    var tr_general = document.createElement('tr');
                        tr_general.setAttribute('class','td text-name-estu tr_cont')
                        var td_estudiante = document.createElement('td');
                            td_estudiante.setAttribute("class", "text-center")
                            td_estudiante.setAttribute("rowspan", "2")
                            td_estudiante.innerHTML = "--"
                        tr_general.append(td_estudiante)

                        if (quimestres != null || quimestres != undefined) {
                            var td_estudiante = document.createElement('td');
                                td_estudiante.setAttribute("class", "text-center")
                                td_estudiante.setAttribute("colspan", calificaciones_examen.length > 0 ? elemento_cantidad + 1 : elemento_cantidad)
                                td_estudiante.innerHTML = quimestres.nombre
                            tr_general.append(td_estudiante)
                        }

                        var td_estudiante = document.createElement('td');
                            td_estudiante.setAttribute("class", "text-center")
                            td_estudiante.setAttribute("rowspan", "2")
                            td_estudiante.innerHTML = "TOTAL"
                        tr_general.append(td_estudiante)
                    document.getElementById('table_calificaciones').append(tr_general)

                    var tr_general = document.createElement('tr');
                        parciales.forEach(element => {
                            var td_estudiante = document.createElement('td');
                                td_estudiante.setAttribute("class", "text-center")
                                td_estudiante.innerHTML = element.nombre
                            tr_general.append(td_estudiante)
                        });
                        if (calificaciones_examen.length > 0) {
                            var td_examen = document.createElement('td');
                                td_examen.setAttribute("class", "text-center")
                                td_examen.innerHTML = "Examen"
                            tr_general.append(td_examen)
                        }
                    document.getElementById('table_calificaciones').append(tr_general)

                if(alumnos.length > 0){
                    alumnos.forEach(element => {
                        const filtro_parcial_x_matriculado = (calificaciones).filter(calificacion => calificacion.matriculado_fk == element.ident_matricula);
                        var tr_general = document.createElement('tr');
                            tr_general.setAttribute('class','td text-name-estu tr_cont')

                            var pn = (element.primer_nombre == null || element.primer_nombre == "" ? '': ((element.primer_nombre).toString()).toUpperCase());
                            var sn = (element.segundo_nombre == null || element.segundo_nombre == "" ? '': ((element.segundo_nombre).toString()).toUpperCase());
                            var pa = (element.apellido_paterno == null || element.apellido_paterno == "" ? '': ((element.apellido_paterno).toString()).toUpperCase());
                            var sa = (element.apellido_materno == null || element.apellido_materno == "" ? '': ((element.apellido_materno).toString()).toUpperCase());

                            var td_estudiante = document.createElement('td');
                                td_estudiante.setAttribute("class", "cant_quim_est_calif")
                                td_estudiante.style.fontSize = "12px";
                                td_estudiante.style.padding = "8px";
                                td_estudiante.innerText = (pn +' '+ sn +' '+ pa +' '+ sa)
                            tr_general.append(td_estudiante)

                            parciales.forEach(parcial => {
                                var td_periodo_x_alumno = document.createElement('td');
                                    td_periodo_x_alumno.setAttribute("class", "text-center quimestre_est_"+element.ident_matricula)
                                    td_periodo_x_alumno.setAttribute("data-type_note", "nota_parcial")

                                    const nota_pa_x_ma = filtro_parcial_x_matriculado.find(p_x_m => p_x_m.parcial_fk == parcial.id);
                                    td_periodo_x_alumno.innerHTML = nota_pa_x_ma == undefined || nota_pa_x_ma == null ? 0 : nota_pa_x_ma.calificacion
                                tr_general.append(td_periodo_x_alumno)
                            });

                            const nota_examen_quimestre = calificaciones_examen.find(e_q => e_q.matriculado_fk == element.ident_matricula);
                            if (nota_examen_quimestre != undefined || nota_examen_quimestre != null) {

                                var td_quimestre = document.createElement('td');
                                    td_quimestre.setAttribute("class", "text-center")
                                    var nota_calificacion = nota_examen_quimestre == undefined || nota_examen_quimestre == null ? 0 : nota_examen_quimestre.calificacion;
                                    td_quimestre.innerHTML = parseFloat(nota_calificacion).toFixed(2)
                                tr_general.append(td_quimestre)
                            }

                            var td_calificacion_final = document.createElement('td');
                                td_calificacion_final.setAttribute("class", "text-center")
                                td_calificacion_final.setAttribute("id", "quimestre_total_"+element.ident_matricula)
                                td_calificacion_final.innerHTML = 0.00;
                            tr_general.append(td_calificacion_final)

                        document.getElementById('table_calificaciones').append(tr_general)

                        actualizarCalificacionXLineaQuimestre(element.ident_matricula);

                        conteo++;
                    });
                }else{
                    calificaciones_examen.length > 0
                    document.getElementById('table_calificaciones').innerHTML += `<tr><td colspan="${calificaciones_examen.length > 0 ? 5 : 4}">No se encontraron datos</td></tr>`
                }
            }else{
                document.getElementById('table_calificaciones').innerHTML = ""
            }
        })
        .catch(error => console.error('MOSTRANDO ERROR GENERAR ESTUDIANTES: '+error));
    }
}

function generarEstudiantesCalificaciones(){
    var periodo = document.getElementById('periodoestudio_calificacion').value
    var idclass = document.getElementById('idasignacion').value
    var token = document.getElementById('tokenasignacion').value
    var option_elemento = getOptionSelectedById('periodoestudio_calificacion').dataset.option;

    if(
        option_elemento == "P" &&
        document.getElementById('idasignacion').value != ""  &&
        document.getElementById('tokenasignacion').value != "" &&
        document.getElementById('periodoestudio_calificacion').value != ""
    ){
        fetch(`/clases/estudiante/detail/estudiantes-calificaciones/${idclass}/${token}/periodo/${periodo}`)
        .then(response => response.json()).then(data => {
            console.log(data)
            document.getElementById('table_calificaciones').innerHTML = ""

            if(data[0] == 200){
                var actividades = data.actividades;
                var calificaciones = data.calificaciones;
                var calificacion_parcial = data.calificacion_parcial;

                var ruta = `/reporte/calificaciones/clase/${idclass}/${(token).toString()}/${data.ident}`;

                document.getElementById('contenedor_boton_nuevo').innerHTML = `
                    <div class="d-flex">
                        <div>
                            <a type="button" class="btn btn-sm btn-primary" href="/clases/estudiante/generar/reporte/calificaciones-actividades/${idclass}/${(token).toString()}/${periodo}" onclick="window.open(this.href,'window','width=1275, height=775'); return false" style="margin-top: 30px;" id="btnGenerarReportesActividades"><i class="ti-printer"></i> PDF Actividades</a>
                            <a type="button" class="btn btn-sm btn-danger" href="${ruta}" onclick="window.open(this.href,'window','width=1275, height=775'); return false" style="margin-top: 30px;" id="btnGenerarReportes"><i class="ti-printer"></i> PDF General</a>
                        </div>
                    </div>
                `;
                var conteo = 1;

                var tr_general = document.createElement('tr');
                    tr_general.setAttribute('class','td text-name-estu tr_cont')
                    var td_actividades = document.createElement('td');
                        td_actividades.setAttribute("class", "text-center")
                        td_actividades.innerHTML = "Actividades"
                    tr_general.append(td_actividades)

                    var td_actividades = document.createElement('td');
                        td_actividades.setAttribute("class", "text-center")
                        td_actividades.innerHTML = "Calificación"
                    tr_general.append(td_actividades)
                document.getElementById('table_calificaciones').append(tr_general)

                if (actividades.length > 0) {
                    actividades.forEach(actividad => {
                        var tr_general = document.createElement('tr');
                            tr_general.setAttribute('class','td text-name-estu tr_cont')
                            var td_actividades = document.createElement('td');
                                td_actividades.setAttribute("class", "text-left")
                                td_actividades.innerHTML = `
                                    <div class="d-flex align-items-center">
                                        <div style="background:${actividad.actividad_color};height:10px;width:10px;"></div>
                                        <div style="margin-left:5px;font-size:11px;">${actividad.actividad_abr}: ${actividad.nombre}</div>
                                    </div>
                                `
                            tr_general.append(td_actividades)
                            const nota_actividad = calificaciones.find(notas => notas.actividad_fk == actividad.id);

                            var td_calificacion = document.createElement('td');
                                td_calificacion.setAttribute("class", "text-center")
                                td_calificacion.innerHTML = nota_actividad == null || nota_actividad == undefined ? 0.00 : nota_actividad.calificacion;
                            tr_general.append(td_calificacion)
                        document.getElementById('table_calificaciones').append(tr_general)
                    });
                    var tr_general = document.createElement('tr');
                        tr_general.setAttribute('class','td text-name-estu tr_cont')
                        var td_actividades = document.createElement('td');
                            td_actividades.setAttribute("class", "text-left")
                            td_actividades.innerHTML = "Calificación total parcial"
                        tr_general.append(td_actividades)

                        var td_calificacion = document.createElement('td');
                            td_calificacion.setAttribute("class", "text-center")
                            var cal_par = calificacion_parcial == null || calificacion_parcial == undefined ? 0.00 : calificacion_parcial.calificacion;
                            td_calificacion.innerHTML = parseFloat(cal_par).toFixed(2);
                        tr_general.append(td_calificacion)
                    document.getElementById('table_calificaciones').append(tr_general)
                } else {
                    document.getElementById('table_calificaciones').innerHTML += `<tr><td colspan="2">No se encontraron actividades.</td></tr>`
                }
            }else{
                document.getElementById('table_calificaciones').innerHTML = ""
            }
        })
        .catch(error => console.error('MOSTRANDO ERROR GENERAR ESTUDIANTES: '+error))
        .finally(()=>{
            $('[data-toggle="tooltip"]').tooltip()
        })
    }
}

function filterOnlyNumber(evt) {
    var key = window.Event ? evt.which : evt.keyCode;
    if (key >= 48 && key <= 57 ||key == 46) {
    return true
    } else {
        return false
    }
}

