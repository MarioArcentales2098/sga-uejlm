function validateReport(params) {
    document.getElementById('cont_report').style.display = "block";
    var n = document.getElementById(params).value
    if(n == ""){
        document.getElementById(params+'-has-error').classList.add('has-error');
        return "error";
    }else{
        document.getElementById(params+'-has-error').classList.remove('has-error');
        return "success";
    }
}
document.querySelector('#btnGenerarReportes').addEventListener('click', ()=>{
    var estu = validateReport('select_asignatura');
    document.getElementById("contenido_reporte_estudiante").innerHTML = "";
    document.getElementById("content_btn_print").innerHTML = "";
    if(estu == "success"){
        // $('#btnGenerarReportes').attr('disabled', true).text('').append(spinner);
        // document.getElementById('cont_report').style.display = "none"

        var slect = document.getElementById('select_asignatura').value
        var elementos = slect.split("-");
        fetch(`/reportes/buscar/calificaciones-estudiantes/clase/${elementos[0]}/token/${(elementos[1]).toString()}`)
        .then(response => response.json()).then(data => {
            if (data) {
                document.getElementById("content_btn_print").innerHTML = "";
                document.getElementById("content_btn_print").innerHTML = `
                    <a href="/reporte/calificaciones/clase/${elementos[0]}/${elementos[1]}/${data.select}" onclick="window.open(this.href,'window','width=1275, height=775');return false" class="btn btn-sm btn-primary" id="btnGenerarPDFReportes"><i class="ti-printer"></i> Generar PDF</a>
                `;

                document.getElementById("contenido_reporte_estudiante").innerHTML = "";

                var tr = document.createElement("tr");
                    var td_est = document.createElement("th");
                        td_est.setAttribute("rowspan", "2");
                        td_est.setAttribute("class", "text-center");
                        td_est.innerHTML = "Estudiante";
                        td_est.style.background = "#ededed";
                    tr.append(td_est);

                    (data.quimestre).forEach(quim => {

                        var td_quim = document.createElement("th");
                            td_quim.setAttribute("colspan", "4");
                            td_quim.setAttribute("class", "text-center");
                            if(quim.nombre == "Quimestre 1"){
                                td_quim.style.background = "#781ad7";
                                td_quim.style.color = "#fff";
                                td_quim.style.borderRight = "none";
                            }else{
                                td_quim.style.background = "#1a88d7";
                                td_quim.style.color = "#fff";
                                td_quim.style.borderLeft = "none";
                            }
                            td_quim.innerHTML = quim.nombre;
                        tr.append(td_quim);
                    });

                    var td_total = document.createElement("th");
                        td_total.setAttribute("rowspan", "2");
                        td_total.setAttribute("class", "text-center");
                        td_total.innerHTML = "TOTAL";
                        td_total.style.background = "#ededed";
                    tr.append(td_total);

                document.getElementById("contenido_reporte_estudiante").append(tr);

                var tr_2 = document.createElement("tr");

                (data.quimestre).forEach(quim => {
                    var contador = 0;
                    (data.parciales).forEach(parcial => {
                        if (parcial.quimestre_fk == quim.id) {
                            var td_random = document.createElement("th");
                                td_random.setAttribute("class", "text-center");
                                td_random.innerHTML = parcial.nombre;
                                td_random.style.background = "#ededed";
                            tr_2.append(td_random);
                            contador++;
                        }
                    });
                    if (contador == 2) {
                        var td_random = document.createElement("th");
                            td_random.setAttribute("class", "text-center");
                            td_random.innerHTML = "EXAMEN";
                            td_random.style.background = "#ededed";
                        tr_2.append(td_random);
                        contador++;
                    }
                    if (contador == 3) {
                        var td_random = document.createElement("th");
                            td_random.setAttribute("class", "text-center");
                            td_random.innerHTML = "TOTAL";
                            td_random.style.background = "#ededed";
                        tr_2.append(td_random);
                    }
                });
                document.getElementById("contenido_reporte_estudiante").append(tr_2);
                var array_array = [];
                if (data.estudiantes.length > 0) {
                    (data.estudiantes).forEach(item => {
                        var tr_3 = document.createElement("tr");

                            var td_estudiante = document.createElement("td");
                                td_estudiante.setAttribute("class", "text-center");
                                td_estudiante.innerHTML = (item.apellido_paterno+" "+item.apellido_materno+" "+item.primer_nombre+" "+item.segundo_nombre).toUpperCase();
                            tr_3.append(td_estudiante);

                            (data.quimestre).forEach(quim => {
                                var contador_parcial = 0;
                                (data.parciales).forEach(parcial => {
                                    if (parcial.quimestre_fk == quim.id) {
                                        var td_random = document.createElement("th");
                                            td_random.setAttribute("class", "text-center");
                                            const elemento_busqueda = (data.calificaciones).find(notas => notas.matriculado_fk == item.ident_matricula && parcial.id == notas.parcial_fk);
                                            var contenido = 0.00;
                                            if (elemento_busqueda != undefined && elemento_busqueda != null) {
                                                contenido = elemento_busqueda.calificacion;
                                            }
                                            td_random.innerHTML = parseFloat(contenido).toFixed(2);
                                        tr_3.append(td_random);
                                        contador_parcial++;
                                    }
                                });
                                if (contador_parcial == 2) {
                                    const elemento_examen = (data.calificaciones_examen).find(notas_ex => notas_ex.quimestre_fk == quim.id && notas_ex.matriculado_fk == item.ident_matricula);
                                    var contenido_ex_quim = elemento_examen != undefined && elemento_examen != null ? elemento_examen.calificacion : 0.00;
                                    var td_random = document.createElement("th");
                                        td_random.setAttribute("class", "text-center");
                                        td_random.innerHTML = parseFloat(contenido_ex_quim).toFixed(2);
                                    tr_3.append(td_random);
                                    contador_parcial++;
                                }

                                if (contador_parcial == 3) {
                                    const elemento_nota_quimestre = (data.calificaciones_quimestre).find(notas_quimestre => notas_quimestre.quimestre_fk == quim.id && notas_quimestre.matriculado_fk == item.ident_matricula);
                                    var contenido_ex_quim = elemento_nota_quimestre != undefined && elemento_nota_quimestre != null ? elemento_nota_quimestre.calificacion : 0.00;
                                    var td_random = document.createElement("th");
                                        td_random.setAttribute("class", "text-center");
                                        td_random.innerHTML = parseFloat(contenido_ex_quim).toFixed(2);
                                    tr_3.append(td_random);
                                    contador_parcial++;
                                    array_array.push(contenido_ex_quim);
                                }
                            });
                            if (array_array.length == data.quimestre.length) {
                                var suma = 0.00;
                                    array_array.forEach(element => {
                                        suma += parseFloat(element);
                                        });
                                    var td_total = document.createElement("td");
                                        td_total.setAttribute("class", "text-center");
                                        td_total.innerHTML = parseFloat(suma).toFixed(2);
                                    tr_3.append(td_total);
                            }
                        document.getElementById("contenido_reporte_estudiante").append(tr_3);
                    });
                }else{
                    document.getElementById("contenido_reporte_estudiante").innerHTML += `<tr><td colspan="10">No se encontraron datos.</td></tr>`;
                }
            }
        })
        .catch(error => console.error('MOSTRANDO ERROR GENERAR ESTUDIANTES: '+error));
    }
});

$(document).ready(function() {
    $('.select-destin').select2();
    document.getElementById('select_asignatura').removeAttribute('disabled')
});
