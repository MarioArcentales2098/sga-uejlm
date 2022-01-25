//============================ CREAR REGISTRO
document.querySelector("#btnCreateRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("nombre");
    var n2 = validateRegisterSimple("nivel");
    var n3 = validateRegisterSimple("paralelo");

    if ( n1 == "success" && n2 == "success" && n3 == "success"){        
        $("#btnCreateRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("nombre", document.getElementById("nombre").value );
        formData.append("nivel", document.getElementById("nivel").value );
        formData.append("paralelo", document.getElementById("paralelo").value );

        fetch('/cursos/crear/curso/fetch', {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            if (data) {
                if (data.success) {
                    // generateRegisters();
                    $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                    toastr.success(data.success);
                    $("#createRegister").modal("hide");
                    limpiarModalRegister();
                }
                if (data.error) {  toastr.error(data.error); }
                if (data.warning) { toastr.warning(data.warning); }
            }
        })
        .catch((error) => { console.error(error); })
        .finally(() => {
            $("#btnCreateRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Guardar`);
        });
    }
});
function limpiarModalRegister(){
    document.getElementById("nombre").value = "";
    document.getElementById("nombre").classList.remove('parsley-error')
}
//============================ EDITAR REGISTRO
$("#editRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    limpiarModalEditRegister();

    document.getElementById("idEditRegistro_fk").value = datos.data("id");
    document.getElementById("edit_nombre").value = datos.data("nombre");
});
document.querySelector("#btnEditRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("edit_nombre");
    if (n1 == "success"){
        $("#btnEditRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("id", document.getElementById("idEditRegistro_fk").value );
        formData.append("nombre", document.getElementById("edit_nombre").value );

        fetch("/cursos/editar/curso/fetch", {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
        .then((response) => response.json())
        .then((data) => {
            if (data) {
                if (data.success) {
                    // generateRegisters();
                    $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                    toastr.success(data.success);
                    limpiarModalEditRegister();
                    $('#editRegister').modal('hide');
                }
                if (data.error) {  toastr.error(data.error); }
                if (data.warning) { toastr.warning(data.warning); }
            }
        })
        .catch((error) => { console.error(error);})
        .finally(() => {
            $("#btnEditRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Guardar`);
        });
    }
});
function limpiarModalEditRegister() {
    document.getElementById("idEditRegistro_fk").value = "";
    document.getElementById("edit_nombre").value = "";
    document.getElementById("edit_nombre").classList.remove('parsley-error')
}

//============================ ASIGNAR MATERIAS
$("#asignRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    document.getElementById("id_asign_registro_fk").value = "";
    document.getElementById("asign-text-register").innerHTML = "";    
    document.getElementById("id_asign_registro_fk").value = datos.data("id");
    document.getElementById("asign-text-register").innerHTML = datos.data("asign_nombre");
    generateCursAsign();
});
function generateCursAsign(){
    var identificador = document.getElementById("id_asign_registro_fk").value
    document.getElementById('content_asig_text').style.display = "none"
    document.getElementById('spinner_asignat').style.display = "block"
    document.getElementById('content_asig').style.display = "none"
    document.getElementById('containerasign').style.display = "none"

    fetch('/listado/asignaturas/'+identificador)
    .then(response => response.json()).then(data => {
        var materias = data[0];
        var asignados = data[1];

        document.getElementById('contenido_asignaturas').innerHTML = ""
       
        if(materias.length > 0){
            document.getElementById('content_asig_text').style.display = "none"
            document.getElementById('spinner_asignat').style.display = "none"
            document.getElementById('content_asig').style.display = "block"
            document.getElementById('containerasign').style.display = "block"
            materias.forEach(element => {    
                generateAsign(element ,asignados)
            });          
        }else{
            document.getElementById('content_asig_text').style.display = "block"
            document.getElementById('content_asig_text').innerHTML = `
            <div>
                <div>No se encontrarón asignaturas creadas.</div>
                <div style="font-size: 12px;" class="text-info">En caso de tener registradas, verificar que las asignaturas tengar asignado docentes.</div>
            </div>
        `
            document.getElementById('spinner_asignat').style.display = "none"
            document.getElementById('content_asig').style.display = "none"
            document.getElementById('containerasign').style.display = "none"
        }
    })
    .catch(error => { console.error('MOSTRANDO ERROR DE CONSULTA DE ASIGNATURAS: '+error)})
    .finally(()=>{

    });    
}
document.querySelector('#btnAsignRegister').addEventListener('click', ()=> {
    var asignaturas = new Array();
    $('input:checkbox[name="asigna[]"]:checked').each(function() {
        var spli = ($(this).prop("id")).split('_')
        if($(this).prop("disabled") != true){
            asignaturas.push({
                'id_asignatura' : spli[1],
                'id_docmateria' : spli[2]
            })
        }
    });

    if(asignaturas.length > 0){
        $("#btnAsignRegister").attr("disabled", true).text("").append(spinner);
    
        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("curso_fk", document.getElementById("id_asign_registro_fk").value );
        formData.append("materias", JSON.stringify(asignaturas));
    
        fetch('/cursos/asignacion/cursos/materias/fetch', {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            if (data) {
                if (data.success) {
                    // generateRegisters();
                    $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                    toastr.success(data.success);
                    $("#asignRegister").modal("hide");
                    limpiarModalAsignRegister();
                }
                if (data.error) {  toastr.error(data.error); }
                if (data.warning) { toastr.warning(data.warning); }
            }
        })
        .catch((error) => { console.error(error); })
        .finally(() => {
            $("#btnAsignRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
        });
    }else{
        toastr.info('No hay cambios que realizar.');
    }

})
function limpiarModalAsignRegister(){
    document.getElementById('content_asig_text').style.display = "none"
    document.getElementById('spinner_asignat').style.display = "none"
    document.getElementById('content_asig').style.display = "none"
    document.getElementById("id_asign_registro_fk").value = "";
    document.getElementById("asign-text-register").innerHTML = "";

    buscador = document.getElementById('searchAsignatura').value = ""
    document.getElementById('containerasign').style.display = "none"
    document.getElementById('spinsearch').style.display = "none"
}
function generateAsign(element, asignados){
    var tr_general = document.createElement('tr');
    var td = document.createElement('td');
        var div_general = document.createElement('div')
            div_general.setAttribute('class', 'col-md-12 card-asign mb-2')

            var div_general = document.createElement('div')
                div_general.setAttribute('class', 'col-md-12 card-asign mb-2')

                var inpcheck = document.createElement('input')
                    inpcheck.setAttribute('class', 'sect-check form-control')
                    inpcheck.setAttribute('type','checkbox')
                    inpcheck.setAttribute('name','asigna[]')
                    inpcheck.setAttribute('id','checkbox_'+element.id+'_'+element.id_relacion_docente_materia)
                    inpcheck.setAttribute('data-clase_fk', '')
                    var asignacionfk = null;
                    asignados.forEach(asign => {
                        if(asign.asign_docmateria_fk == element.id_relacion_docente_materia){
                            asignacionfk = asign.id
                            inpcheck.setAttribute('checked', true)
                            inpcheck.setAttribute('disabled', true)
                            div_general.setAttribute('class', 'col-md-12 card-asign-true mb-2')
                        }
                    });                    
                var div_content = document.createElement('div')
                    div_content.setAttribute('class', 'card-asign-content')
                    var span_title = document.createElement('span')
                        span_title.setAttribute('class', 'card-asign-title')
                        span_title.innerText = "NOMBRE: "+element.nombre
                    div_content.append(span_title)

                    var span_doc = document.createElement('span')
                        span_doc.setAttribute('class', 'card-asign-desc text-uppercase')
                        span_doc.innerText = "DOCENTE: "+element.usuario_papellido +' '+ element.usuario_sapellido +' '+ element.usuario_pnombre +' '+ element.usuario_snombre;
                    div_content.append(span_doc)

                    var span_cod = document.createElement('span')
                        span_cod.setAttribute('class', 'card-asign-desc')
                        span_cod.innerText = "CÓDIGO: "+element.codigo_asignatura + element.codigo_asignatura_num
                    div_content.append(span_cod)

                var div_content_del = document.createElement('div')
                    div_content_del.setAttribute('id', 'checkboxdelete_'+asignacionfk)
                    div_content_del.setAttribute('class', 'ml-auto text-danger')
                    div_content_del.style.display = "flex";
                    div_content_del.style.alignItems = "center";                    
                    div_content_del.style.marginBottom = "16px";
                    div_content_del.style.cursor = "pointer";
                    div_content_del.innerHTML = `<i class="fa fa-trash" title="Eliminar asignación"  onclick="deleteRelacionDA(${asignacionfk})"></i>`

            div_general.append(inpcheck)
            div_general.append(div_content)
            asignados.forEach(asign => {
                if(asign.asign_docmateria_fk == element.id_relacion_docente_materia){
                    div_general.append(div_content_del)            
                }
            });
        td.append(div_general)
    tr_general.append(td)
    document.getElementById('contenido_asignaturas').append(tr_general)
}
$("#searchAsignatura").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#contenido_asignaturas tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

//=========================== ELIMINAR REGISTER
$("#deleteRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    document.getElementById("id_delete_registro_fk").value = "";
    document.getElementById("delete-text-register").innerHTML = "";

    document.getElementById("id_delete_registro_fk").value = datos.data("id");
    document.getElementById("delete-text-register").innerHTML = datos.data("edit_nombre");    
});
document.querySelector("#btnDeleteRegister").addEventListener("click", () => {
    $("#btnDeleteRegister").attr("disabled", true).text("").append(spinner);

    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("id", document.getElementById("id_delete_registro_fk").value);

    fetch("/cursos/delete/curso/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        if (data) {
            if (data.success) {
                // generateRegisters();
                $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                toastr.success(data.success);
                limpiarModalDeleteRegister();
                $("#deleteRegister").modal("hide");
            }
            if (data.error) {  toastr.error(data.error); }
            if (data.warning) { toastr.warning(data.warning); }
        }
    })
    .catch((error) => {
        console.error(error);
    })
    .finally(() => {
        $("#btnDeleteRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
    });
 
});
function limpiarModalDeleteRegister() {
    document.getElementById("id_delete_registro_fk").value = "";
}
$("#searchAsignatura").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#contenido_asignaturas tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

//============================ DELETE ASIGN REGISTER
function deleteRelacionDA(idasignacion) {    
    if(confirm('¿Está seguro de eliminar esta asignación?')){
        document.getElementById('checkboxdelete_'+idasignacion).innerHTML = ""
        document.getElementById('checkboxdelete_'+idasignacion).style.cursor = null;
        $("#checkboxdelete_"+idasignacion).append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("idasignacion", idasignacion);
    
        var condicion = new Array();
        fetch('/cursos/delete/asignacion/asignaturas/fetch' ,{method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then(response => response.json()).then(data => {
            if (data) {
                if (data.success) { condicion.push('success'); toastr.success(data.success); }
                if (data.error) {  condicion.push('error'); toastr.error(data.error);}
                if (data.warning) { condicion.push('error'); toastr.warning(data.warning); }
            }
        })
        .catch(error => {
            document.getElementById('checkboxdelete_'+idasignacion).innerHTML = `<i class="fa fa-trash" title="Eliminar asignación"  onclick="deleteRelacionDA(${idasignacion})"></i>`
            document.getElementById('checkboxdelete_'+idasignacion).style.cursor = "pointer";
            console.error('MOSTRANDO ERROR DELETE ASIGN: '+error)
            toastr.error('Algo salió mal, vuelva a intentarlo');
        })
        .finally(()=>{
            if(condicion[0] == "success"){
                generateCursAsign();
            }
            if(condicion[0] == "error"){
                document.getElementById('checkboxdelete_'+idasignacion).innerHTML = `<i class="fa fa-trash" title="Eliminar asignación"  onclick="deleteRelacionDA(${idasignacion})"></i>`
                document.getElementById('checkboxdelete_'+idasignacion).style.cursor = "pointer";
            }
        })
    }
}