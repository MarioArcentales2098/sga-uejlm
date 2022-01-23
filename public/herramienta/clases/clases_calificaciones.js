$(document).ready(function(){
    $("#input_calificaciones").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_calificaciones tr").filter(function(){$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)});
    });
});



function generarmasfila(){
    var table = document.getElementById('table_asistencia');
    var rowCount = table.rows.length;
    for(var i= 0; i< rowCount; i++){
        var row = table.rows[i];
        ////console.log(row)
            // var td = document.createElement('td')
                // td.innerText = i

        // row.append(td)
    }
}

function limpiarModalRegisterActivity() {
    $("#tipo_actividad").select2().val("");
    $("#tipo_actividad").select2().val("");
    document.getElementById("nombre_actividad").value = "";
    document.getElementById("descripcion_actividad").value = "";
    document.getElementById("fecha_actividad").value = document.getElementById("fecha_default").value;

    document.getElementById('tipo_actividad').classList.remove('parsley-error');
    document.getElementById('nombre_actividad').classList.remove('parsley-error');
    document.getElementById('descripcion_actividad').classList.remove('parsley-error');
    document.getElementById('fecha_actividad').classList.remove('parsley-error');
}

function limpiarModalRegisterExamen() {
    document.getElementById("nombre_examen_quimestral").value = "";
    document.getElementById("descripcion_examen_quimestral").value = "";
    document.getElementById("fecha_examen_quimestral").value = document.getElementById("fecha_default").value;

    document.getElementById('nombre_examen_quimestral').classList.remove('parsley-error');
    document.getElementById('descripcion_examen_quimestral').classList.remove('parsley-error');
    document.getElementById('fecha_examen_quimestral').classList.remove('parsley-error');
}


function crearBotonNuevaActividad(ubicacion, class_id, class_token) {
    document.getElementById(ubicacion).innerHTML = `
        <div class="d-flex">
            <div>
                <button type="button" class="btn btn-sm btn-success" style="margin-top: 30px;" data-toggle="modal" data-target="#crearNuevaTarea" id="añadir_nueva_actividad"><i class="fa fa-plus"></i> Nueva actividad</button>
                <a type="button" class="btn btn-sm btn-primary" href="/clases/${class_id}/token/${class_token}/generar/reporte/calificaciones-actividades" onclick="window.open(this.href,'window','width=1275, height=775'); return false" style="margin-top: 30px;" id="btnGenerarReportesActividades"><i class="ti-printer"></i> PDF Actividades</a>
                <a type="button" class="btn btn-sm btn-danger" href="/clases/${class_id}/token/${class_token}/generar/reporte/calificaciones" onclick="window.open(this.href,'window','width=1275, height=775'); return false" style="margin-top: 30px;" id="btnGenerarReportes"><i class="ti-printer"></i> PDF General</a>
            </div>
        </div>
    `;
}


function crearBotonNuevoExamenQuimestre(ubicacion, class_id, class_token) {
    document.getElementById(ubicacion).innerHTML = `
        <div class="d-flex">
            <div>
                <button type="button" class="btn btn-sm btn-success" style="margin-top: 30px;" data-toggle="modal" data-target="#crearNuevoExamenQuimestre" id="añadir_nuevo_examen_quimestre"><i class="fa fa-plus"></i> Nuevo Examen</button>
            </div>
            <div class="ml-auto">
                <a type="button" class="btn btn-sm btn-danger" href="/clases/${class_id}/token/${class_token}/generar/reporte/calificaciones" onclick="window.open(this.href,'window','width=1275, height=775'); return false" style="margin-top: 30px;" id="btnGenerarReportes"><i class="ti-printer"></i> PDF General</a>
            </div>
        </div>
    `;
}

function actualizarCalificacion(elemento, ident_est, ident_act){
    actualizarCalificacionXLinea(ident_est);

    var cal_final = document.getElementById("nota_final_"+ident_est).innerHTML;
    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("calificacion", elemento.value);
    formData.append("calificacion_final", cal_final);
    formData.append("ident_est", ident_est);
    formData.append("ident_act", ident_act);

    formData.append("idasignacion", document.getElementById("idasignacion").value );
    formData.append("tokenasignacion", document.getElementById("tokenasignacion").value );
    formData.append("periodo", document.getElementById("periodoestudio_calificacion").value );

    fetch("/clases/actualizar/calificacion-actividad/parcial/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
    .then((response) => response.json()).then((data) => {
        console.log("...");
    })
    .catch((error) => { console.error(error); })
    .finally(() => {
    });
}

function actualizarCalificacionXLinea(identificador) {
    var data_group = document.getElementsByClassName("est_"+identificador);
    var contenido = new Array();
    var actividades = new Array();

    for (i = 0; i < data_group.length; i++) {
        const elementos = new Object();
        elementos.identificador = data_group[i].id;
        elementos.actividad = data_group[i].dataset.actividad;
        elementos.porcentaje = data_group[i].dataset.porcentaje;
        elementos.calificacion = data_group[i].value > 0 ? data_group[i].value : 0;

        var found = actividades.find(element => element.actividad == data_group[i].dataset.actividad);

        if (found == undefined) {
            const data_actividad = new Object();
            data_actividad.actividad = data_group[i].dataset.actividad;
            data_actividad.porcentaje = data_group[i].dataset.porcentaje;
            data_actividad.actividad_max_calificacion = data_group[i].dataset.actividad_max_calificacion;
            actividades.push(data_actividad);
        }
        contenido.push(elementos);
    }

    var sumatoria_total = 0.00;

    actividades.forEach(actividad => {
        const filtro_tarea_x_matriculado = (contenido).filter(calificaciones => calificaciones.actividad == actividad.actividad);
        var sumatoria = 0.00;

        filtro_tarea_x_matriculado.forEach(qlo => {
            sumatoria += parseFloat(qlo.calificacion)
        });

        var division = sumatoria / filtro_tarea_x_matriculado.length
        var puntos_posibles = actividad.actividad_max_calificacion * actividad.porcentaje / 100;
        var total_x_actividad = division * puntos_posibles / actividad.actividad_max_calificacion;

        sumatoria_total += total_x_actividad;
    });
    document.getElementById("nota_final_"+identificador).innerHTML = parseFloat(sumatoria_total).toFixed(2);
}

function verificarMaximo(params) {
    var valor = document.getElementById(params).value;
    var maximo = document.getElementById(params).dataset.actividad_max_calificacion;
    if (parseFloat(valor) > parseFloat(maximo) || parseFloat(valor) < 0) {
        document.getElementById(params).value = 0;
    }
}

function eliminarActividad(identificador) {
    if (identificador != "" && identificador != null && identificador != undefined) {
        if (confirm("Estas seguro que deseas eliminar esta actividad?")) {
            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData();
            formData.append("identificador", identificador);
            formData.append("idasignacion", document.getElementById("idasignacion").value );
            formData.append("tokenasignacion", document.getElementById("tokenasignacion").value );
            formData.append("periodo", document.getElementById("periodoestudio_calificacion").value );

            fetch("/clases/eliminar/actividad/parcial/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
            .then((response) => response.json()).then((data) => {
                if (data) {
                    if (data.success) {
                        toastr.success(data.success);
                        seleccionacionar_contenido();
                    }
                    if (data.error) {  toastr.error(data.error); }
                    if (data.warning) { toastr.warning(data.warning); }
                }
            })
            .catch((error) => { console.error(error); })
            .finally(() => {
            });
        }
    } else {
        toastr.error("Algo salio mal, vuelva a intentarlo, recargue la pagina")
    }
}

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

function verificarMaximoQuimestre(params) {
    var valor = document.getElementById(params).value;
    var maximo = 10;
    if (parseFloat(valor) > parseFloat(maximo) || parseFloat(valor) < 0) {
        document.getElementById(params).value = 0;
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


function actualizarCalificacionQuimestre(elemento, ident_est, ident_quim){
    actualizarCalificacionXLineaQuimestre(ident_est);

    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("calificacion", elemento.value);
    formData.append("calificacion_final", document.getElementById("quimestre_total_"+ident_est).innerHTML);
    formData.append("ident_est", ident_est);
    formData.append("ident_quim", ident_quim);

    formData.append("idasignacion", document.getElementById("idasignacion").value );
    formData.append("tokenasignacion", document.getElementById("tokenasignacion").value );
    formData.append("periodo", document.getElementById("periodoestudio_calificacion").value );

    fetch("/clases/actualizar/calificacion-examen/quiemstre/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
    .then((response) => response.json()).then((data) => {
        console.log("...");
    })
    .catch((error) => { console.error(error); })
    .finally(() => {
    });
}

function eliminarExamenQuimestre(identificador) {
    if (identificador != "" && identificador != null && identificador != undefined) {
        if (confirm("Estas seguro que deseas eliminar este examen?")) {
            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData();
            formData.append("identificador", identificador);
            formData.append("idasignacion", document.getElementById("idasignacion").value );
            formData.append("tokenasignacion", document.getElementById("tokenasignacion").value );
            formData.append("periodo", document.getElementById("periodoestudio_calificacion").value );

            fetch("/clases/eliminar/examen/quimestre/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
            .then((response) => response.json()).then((data) => {
                if (data) {
                    if (data.success) {
                        toastr.success(data.success);
                        seleccionacionar_contenido();
                    }
                    if (data.error) {  toastr.error(data.error); }
                    if (data.warning) { toastr.warning(data.warning); }
                }
            })
            .catch((error) => { console.error(error); })
            .finally(() => {
            });
        }
    } else {
        toastr.error("Algo salio mal, vuelva a intentarlo, recargue la pagina")
    }
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
        fetch(`/clases/detail/estudiantes-calificaciones-quimestres/${idclass}/${token}/periodo/${periodo}`)
        .then(response => response.json()).then(data => {
            document.getElementById('table_calificaciones').innerHTML = ""

            if(data[0] == 200){
                var alumnos = data.estudiantes;
                var quimestres = data.quimestres;
                var parciales = data.parciales;
                var calificaciones = data.calificaciones;
                var calificaciones_examen = data.calificaciones_examen;

                var elemento_cantidad = 2;
                crearBotonNuevoExamenQuimestre("contenedor_boton_nuevo", idclass, (token).toString());

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
                                td_estudiante.innerText = (conteo+'.-'+pn +' '+ sn +' '+ pa +' '+ sa)
                            tr_general.append(td_estudiante)

                            parciales.forEach(parcial => {
                                var td_periodo_x_alumno = document.createElement('td');
                                    td_periodo_x_alumno.setAttribute("class", "text-center quimestre_est_"+element.ident_matricula)
                                    td_periodo_x_alumno.setAttribute("data-type_note", "nota_parcial")

                                    const nota_pa_x_ma = filtro_parcial_x_matriculado.find(p_x_m => p_x_m.parcial_fk == parcial.id);
                                    var nota_calificacion = nota_pa_x_ma == undefined || nota_pa_x_ma == null ? 0.00 : nota_pa_x_ma.calificacion;
                                    td_periodo_x_alumno.innerHTML = parseFloat(nota_calificacion).toFixed(2);
                                tr_general.append(td_periodo_x_alumno)
                            });

                            const nota_examen_quimestre = calificaciones_examen.find(e_q => e_q.matriculado_fk == element.ident_matricula);
                            if (nota_examen_quimestre != undefined || nota_examen_quimestre != null) {

                                var td_quimestre = document.createElement('td');
                                    td_quimestre.setAttribute("class", "text-center")
                                    var input_calificacion = document.createElement("input");
                                        input_calificacion.setAttribute("type", "number")
                                        input_calificacion.style.border = "none"
                                        input_calificacion.style.backgroundColor = "transparent"
                                        input_calificacion.style.width = "80px";
                                        input_calificacion.setAttribute("min", "0.00")
                                        input_calificacion.setAttribute("step", "0.10")
                                        input_calificacion.setAttribute("id", "cal_exam_"+element.ident_matricula+"_"+nota_examen_quimestre.id)
                                        input_calificacion.setAttribute("class", "quimestre_est_"+element.ident_matricula)
                                        input_calificacion.setAttribute("data-type_note", "nota_examen")
                                        input_calificacion.setAttribute("onkeyup", `verificarMaximoQuimestre(this.id); actualizarCalificacionXLineaQuimestre(${element.ident_matricula});`)
                                        input_calificacion.setAttribute("onchange", `actualizarCalificacionQuimestre(this, ${element.ident_matricula}, ${nota_examen_quimestre.id});`)
                                        input_calificacion.setAttribute("value", nota_examen_quimestre == undefined || nota_examen_quimestre == null ? 0 : nota_examen_quimestre.calificacion)
                                    td_quimestre.append(input_calificacion)
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
        fetch(`/clases/detail/estudiantes-calificaciones/${idclass}/${token}/periodo/${periodo}`)
        .then(response => response.json()).then(data => {
            document.getElementById('table_calificaciones').innerHTML = ""

            if(data[0] == 200){
                var alumnos = data.estudiantes;
                var actividades = data.actividades;
                crearBotonNuevaActividad("contenedor_boton_nuevo", idclass, (token).toString());
                    var conteo = 1;

                    var tr_general = document.createElement('tr');
                        tr_general.setAttribute('class','td text-name-estu tr_cont')
                        var td_estudiante = document.createElement('td');
                            td_estudiante.setAttribute("class", "text-center")
                            if (actividades.length > 0) {
                                td_estudiante.style.border = "none"
                                td_estudiante.style.borderTop = "solid 1px #dee2e6"
                            }
                            td_estudiante.innerHTML = "--"
                        tr_general.append(td_estudiante)

                        actividades.forEach(element => {
                            var td_estudiante = document.createElement('td');
                                td_estudiante.setAttribute("class", "text-center text-activity")
                                td_estudiante.setAttribute("data-toggle", "tooltip")
                                td_estudiante.setAttribute("data-placement", "left")
                                td_estudiante.setAttribute("title", element.actividad_nombre)
                                td_estudiante.setAttribute("onclick", "eliminarActividad("+element.id+")")
                                td_estudiante.style.backgroundColor = element.actividad_color
                                td_estudiante.innerHTML = `<div style="transform: rotate(270deg);"><span class="text-strong" >${element.nombre}</span></div>`
                            tr_general.append(td_estudiante)
                        });

                        if (actividades.length > 0) {
                            var td_total = document.createElement('td');
                                td_total.style.height = "110px";
                                td_total.style.width = "105px";
                                td_total.style.border = "none";
                                td_total.style.borderTop = "solid 1px #dee2e6";
                                td_total.style.padding = "0px";
                                td_total.setAttribute("class", "text-center");
                                td_total.innerHTML = `<div style="transform: rotate(270deg);">Calificación final</div>`;
                            tr_general.append(td_total)
                        }
                    document.getElementById('table_calificaciones').append(tr_general)
                    if(alumnos.length > 0){

                    alumnos.forEach(element => {
                        const filtro_tarea_x_matriculado = (data.calificaciones).filter(calificaciones => calificaciones.matriculado_fk == element.ident_matricula);
                        var tr_general = document.createElement('tr');
                            tr_general.setAttribute('class','td text-name-estu tr_cont')

                            var pn = (element.primer_nombre == null || element.primer_nombre == "" ? '': ((element.primer_nombre).toString()).toUpperCase());
                            var sn = (element.segundo_nombre == null || element.segundo_nombre == "" ? '': ((element.segundo_nombre).toString()).toUpperCase());
                            var pa = (element.apellido_paterno == null || element.apellido_paterno == "" ? '': ((element.apellido_paterno).toString()).toUpperCase());
                            var sa = (element.apellido_materno == null || element.apellido_materno == "" ? '': ((element.apellido_materno).toString()).toUpperCase());

                            var td_estudiante = document.createElement('td');
                                td_estudiante.setAttribute("class", "cant_est_calif")
                                td_estudiante.style.fontSize = "12px";
                                td_estudiante.style.padding = "8px";
                                td_estudiante.innerText = (conteo+'.-'+pn +' '+ sn +' '+ pa +' '+ sa)
                            tr_general.append(td_estudiante)

                            actividades.forEach(actividad => {
                                var td_activity_x_alumno = document.createElement('td');
                                    const nota_ac_x_ma = filtro_tarea_x_matriculado.find(t_x_m => t_x_m.actividad_fk == actividad.id);
                                    var input_calificacion = document.createElement("input");
                                        input_calificacion.setAttribute("type", "number")
                                        input_calificacion.style.border = "none"
                                        input_calificacion.style.backgroundColor = "transparent"
                                        input_calificacion.style.width = "80px";

                                        input_calificacion.setAttribute("min", "0.00")
                                        input_calificacion.setAttribute("max", actividad.actividad_max_calificacion)
                                        input_calificacion.setAttribute("step", "0.10")

                                        input_calificacion.setAttribute("id", element.ident_matricula+"_"+actividad.id)
                                        input_calificacion.setAttribute("class", "text-center est_"+element.ident_matricula)
                                        input_calificacion.setAttribute("data-actividad", actividad.actividad_fk)
                                        input_calificacion.setAttribute("data-porcentaje", actividad.actividad_porcentaje)
                                        input_calificacion.setAttribute("data-actividad_max_calificacion", actividad.actividad_max_calificacion)
                                        input_calificacion.setAttribute("onkeypress", "return filterOnlyNumber(event)");
                                        input_calificacion.setAttribute("onkeyup", `verificarMaximo(this.id); actualizarCalificacionXLinea(${element.ident_matricula});`)
                                        input_calificacion.setAttribute("onchange", `actualizarCalificacion(this, ${element.ident_matricula}, ${actividad.id});`)
                                        input_calificacion.setAttribute("value", nota_ac_x_ma.calificacion)
                                    td_activity_x_alumno.append(input_calificacion)
                                tr_general.append(td_activity_x_alumno)
                            });

                            if (actividades.length > 0) {
                                var td_total_estudiante = document.createElement('td');
                                    td_total_estudiante.setAttribute('class','text-center')
                                    td_total_estudiante.style.width = "80px";
                                    td_total_estudiante.setAttribute("id", "nota_final_"+element.ident_matricula)
                                    td_total_estudiante.innerHTML = 0
                                tr_general.append(td_total_estudiante)

                            }
                        document.getElementById('table_calificaciones').append(tr_general)
                        if (actividades.length > 0) {
                            actualizarCalificacionXLinea(element.ident_matricula);
                        }

                        conteo++;
                    });
                }else{
                   document.getElementById('table_calificaciones').innerHTML += `<tr><td colspan="${actividades.length + 2}"> No se encontraron elementos.</td></tr>`
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

function prueba(alumnos, actividades, calificaciones) {
    var var_matriculados = new Array();

    alumnos.forEach(element => {
        const pa_actualizar = new Object();
        var var_contenido = new Array();
        var var_actividades = new Array();

        const calificacion_nota_v_1 = (calificaciones).filter(x_calificaciones => x_calificaciones.matriculado_fk == element.ident_matricula);
        actividades.forEach(actividad => {
            const elementos = new Object();
            elementos.actividad = actividad.actividad_fk;
            elementos.porcentaje = actividad.actividad_porcentaje;
            const calificacion_nota_v_2 = calificacion_nota_v_1.find(y_calificaciones => y_calificaciones.actividad_fk == actividad.id);
            elementos.calificacion = calificacion_nota_v_2 == undefined || calificacion_nota_v_2 == null ? 0.00 : calificacion_nota_v_2.calificacion;

            var found = var_actividades.find(x_element => x_element.actividad == actividad.actividad_fk);

            if (found == undefined) {
                const data_actividad = new Object();
                data_actividad.actividad = actividad.actividad_fk;
                data_actividad.porcentaje = actividad.actividad_porcentaje;
                data_actividad.actividad_max_calificacion = actividad.actividad_max_calificacion;
                var_actividades.push(data_actividad);
            }
            var_contenido.push(elementos);
        });

        var sumatoria_total = 0.00;

        var_actividades.forEach(actividad => {
            const filtro_tarea_x_matriculado = (var_contenido).filter(calificaciones => calificaciones.actividad == actividad.actividad);
            var sumatoria = 0.00;

            filtro_tarea_x_matriculado.forEach(qlo => {
                sumatoria += parseFloat(qlo.calificacion)
            });

            var division = sumatoria / filtro_tarea_x_matriculado.length
            var puntos_posibles = actividad.actividad_max_calificacion * actividad.porcentaje / 100;
            var total_x_actividad = division * puntos_posibles / actividad.actividad_max_calificacion;

            sumatoria_total += total_x_actividad;
        });
        pa_actualizar.matriculado = element.ident_matricula;
        pa_actualizar.sumatoria = sumatoria_total;

        var_matriculados.push(pa_actualizar);
    });

    return var_matriculados;
}

function guardarCalificacionesGeneral(datos) {
    var option_elemento = getOptionSelectedById('periodoestudio_calificacion').dataset.option;

    if(
        option_elemento == "P" &&
        document.getElementById('idasignacion').value != ""  &&
        document.getElementById('tokenasignacion').value != "" &&
        document.getElementById('periodoestudio_calificacion').value != ""
    ){
        var array_objetos = [];
        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();

        formData.append("idasignacion", document.getElementById("idasignacion").value );
        formData.append("tokenasignacion", document.getElementById("tokenasignacion").value );
        formData.append("periodo", document.getElementById("periodoestudio_calificacion").value );
        formData.append('datos' , JSON.stringify(datos))

        fetch("/clases/actualizar/calificacion-general/parcial/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            console.log("...");
        })
        .catch(error => console.error('MOSTRANDO ERROR GENERAR ESTUDIANTES: '+error));
    }
}

function funcionSalvaVidasxD(){
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
        var array_objetos = [];
        fetch(`/clases/detail/estudiantes-calificaciones/${idclass}/${token}/periodo/${periodo}`)
        .then(response => response.json()).then(data => {
            if(data[0] == 200){
                var alumnos = data.estudiantes;
                var actividades = data.actividades;
                var calificaciones = data.calificaciones;
                array_objetos = prueba(alumnos, actividades, calificaciones);
            }
        })
        .catch(error => console.error('MOSTRANDO ERROR GENERAR ESTUDIANTES: '+error))
        .finally(() => {
            guardarCalificacionesGeneral(array_objetos);
        });
    }
}

function validateRegisterNoSimple(params) {
    var e = document.getElementById(params).value;

    if (e == "") {
        document.getElementById(params+"-has-error").classList.add("has-error")
        return "error"
    } else {
        document.getElementById(params+"-has-error").classList.remove("has-error")
        return "success"
    }
}

document.querySelector("#btnCreateActivity").addEventListener("click", () => {

    var n1 = validateRegisterNoSimple("tipo_actividad");
    var n2 = validateRegisterSimple("nombre_actividad");
    var n3 = validateRegisterSimple("descripcion_actividad");
    var n4 = validateRegisterSimple("fecha_actividad");


    if ( n1 == "success" &&   n2 == "success" &&   n3 == "success" && n4 == "success"){
        if(
            document.getElementById('idasignacion').value != ""  &&
            document.getElementById('tokenasignacion').value != ""
        ){
            $("#btnCreateActivity").attr("disabled", true).text("").append(spinner);

            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData();
            formData.append("tipo_actividad", document.getElementById("tipo_actividad").value );
            formData.append("nombre_actividad", document.getElementById("nombre_actividad").value);
            formData.append("descripcion_actividad",document.getElementById("descripcion_actividad").value );
            formData.append("fecha_actividad", document.getElementById("fecha_actividad").value );
            formData.append("idasignacion", document.getElementById("idasignacion").value );
            formData.append("tokenasignacion", document.getElementById("tokenasignacion").value );
            formData.append("periodo", document.getElementById("periodoestudio_calificacion").value );

            fetch("/clases/crear/actividad/parcial/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
            .then((response) => response.json()).then((data) => {
                if (data) {
                    if (data.success) {
                        toastr.success(data.success);
                        seleccionacionar_contenido();
                        $("#crearNuevaTarea").modal("hide");
                        limpiarModalRegisterActivity();
                    }
                    if (data.error) {  toastr.error(data.error); }
                    if (data.warning) { toastr.warning(data.warning); }
                }
            })
            .catch((error) => { console.error(error); })
            .finally(() => {
                funcionSalvaVidasxD();
                $("#btnCreateActivity").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Guardar`);
            });
        }

    }
});


{/* <button type="button" class="btn btn-sm btn-success" style="margin-top: 30px;" data-toggle="modal" data-target="#crearNuevoExamenQuimestre" id="añadir_nuevo_examen_quimestre"><i class="fa fa-plus"></i> Nuevo Examen</button> */}


document.querySelector("#btnCreateExamenQuimestral").addEventListener("click", () => {
    var n1 = validateRegisterSimple("nombre_examen_quimestral");
    var n2 = validateRegisterSimple("descripcion_examen_quimestral");
    var n3 = validateRegisterSimple("fecha_examen_quimestral");


    if ( n1 == "success" &&   n2 == "success" &&   n3 == "success"){
        if(
            document.getElementById('idasignacion').value != ""  &&
            document.getElementById('tokenasignacion').value != ""
        ){
            $("#btnCreateExamenQuimestral").attr("disabled", true).text("").append(spinner);

            let csrf = document.querySelector("input[name='_token']");
            const formData = new FormData();
            formData.append("nombre_examen_quimestral", document.getElementById("nombre_examen_quimestral").value);
            formData.append("descripcion_examen_quimestral",document.getElementById("descripcion_examen_quimestral").value );
            formData.append("fecha_examen_quimestral", document.getElementById("fecha_examen_quimestral").value );
            formData.append("idasignacion", document.getElementById("idasignacion").value );
            formData.append("tokenasignacion", document.getElementById("tokenasignacion").value );
            formData.append("periodo", document.getElementById("periodoestudio_calificacion").value );

            fetch("/clases/crear/examen/quimestral/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
            .then((response) => response.json()).then((data) => {
                if (data) {
                    if (data.success) {
                        toastr.success(data.success);
                        seleccionacionar_contenido();
                        $("#crearNuevoExamenQuimestre").modal("hide");
                        limpiarModalRegisterExamen();
                    }
                    if (data.error) {  toastr.error(data.error); }
                    if (data.warning) { toastr.warning(data.warning); }
                }
            })
            .catch((error) => { console.error(error); })
            .finally(() => {
                $("#btnCreateExamenQuimestral").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Guardar`);
            });
        }

    }
});

function filterOnlyNumber(evt) {
    var key = window.Event ? evt.which : evt.keyCode;
    if (key >= 48 && key <= 57 ||key == 46) {
    return true
    } else {
        return false
    }
}

