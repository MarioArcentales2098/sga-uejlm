//============================ CREAR REGISTRO
document.querySelector("#btnCreateRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("nombre");

    if ( n1 == "success"){        
        $("#btnCreateRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("nombre", document.getElementById("nombre").value );
        formData.append("codigo_asignatura", document.getElementById("codigo_asignatura").value );
        formData.append("codigo_asignatura_num", document.getElementById("countasig").value );    
        
        fetch('/asignaturas/crear/asignatura/fetch', {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            console.log(data)
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
    document.getElementById("nombre").classList.remove('parsley-error');
    document.getElementById("codigo_asignatura").value = "";
    document.getElementById("codigo_asignatura").classList.remove('parsley-error');
    document.getElementById("docente").value = "";
    document.getElementById("docente").classList.remove('parsley-error');
}
//============================ EDITAR REGISTRO
$("#editRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    limpiarModalEditRegister();
    document.getElementById("idEditRegistro_fk").value = datos.data("id");
    document.getElementById("edit_nombre").value = datos.data("nombre"); 
    document.getElementById('edit_countasig').value = datos.data('countasig');
    generateFirstLetter('edit_nombre','edit_codigo_asignatura');
});
document.querySelector("#btnEditRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("edit_nombre");

    if ( n1 == "success"){        
        $("#btnEditRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("id", document.getElementById("idEditRegistro_fk").value );
        formData.append("nombre", document.getElementById("edit_nombre").value );
        formData.append("codigo_asignatura", document.getElementById("edit_codigo_asignatura").value );         

        fetch("/asignaturas/editar/asignatura/fetch", {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
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
    document.getElementById("edit_codigo_asignatura").value = "";
    document.getElementById('edit_countasig').value = ""
}
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

    fetch("/asignaturas/delete/asignatura/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
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
function generateFirstLetter(obtener, enviar){
    var abbr = ((document.getElementById(obtener).value).split(' ').map((item) => {return item[0]; }).join('')).toString().toUpperCase();
    var count = '';
    if(obtener == "nombre"){
        count = document.getElementById('countasig').value
    }
    if(obtener == "edit_nombre"){
        count = document.getElementById('edit_countasig').value
    }
    document.getElementById(enviar).value = (abbr+count)
}

//============================ ASIGNAR DOCENTES
$("#asignRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    document.getElementById("id_asign_registro_fk").value = "";
    document.getElementById("asign-text-register").innerHTML = "";

    document.getElementById("id_asign_registro_fk").value = datos.data("id");
    document.getElementById("asign-text-register").innerHTML = datos.data("nombre"); 
    generateDocAsign();
});
function generateDocAsign(){
    document.getElementById('content_asig_text').style.display = "none"
    document.getElementById('spinner_asignat').style.display = "block"
    document.getElementById('content_asig').style.display = "none"
    document.getElementById('containerasign').style.display = "none"

    var identificador = document.getElementById("id_asign_registro_fk").value
    fetch('/listado/docentes/'+identificador)
    .then(response => response.json()).then(data => {
        var docentes = data[0];
        var asignados = data[1];

        document.getElementById('contenido_docentes').innerHTML = ""
       
        if(docentes.length > 0){
            document.getElementById('content_asig_text').style.display = "none"
            document.getElementById('spinner_asignat').style.display = "none"
            document.getElementById('content_asig').style.display = "block"
            document.getElementById('containerasign').style.display = "block"
            docentes.forEach(element => {    
                generateAsign(element ,asignados)
            });          
        }else{
            document.getElementById('content_asig_text').style.display = "block"
            document.getElementById('content_asig_text').innerHTML = "No se encontrarón docentes registrados."
            document.getElementById('spinner_asignat').style.display = "none"
            document.getElementById('content_asig').style.display = "none"
            document.getElementById('containerasign').style.display = "none"
        }
    })
    .catch(error => { console.error('MOSTRANDO ERROR DE CONSULTA DE ASIGNATURAS: '+error)})  
}
document.querySelector('#btnAsignRegister').addEventListener('click', ()=> {
    var docentes = new Array();
    $('input:checkbox[name="asigna[]"]:checked').each(function() {
        var spli = ($(this).prop("id")).split('_')
        if($(this).prop("disabled") != true){
            docentes.push({
                'id_docente' : spli[1]
            })
        }
    });

    if(docentes.length > 0){
        $("#btnAsignRegister").attr("disabled", true).text("").append(spinner);
    
        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("asignatura_fk", document.getElementById("id_asign_registro_fk").value );
        formData.append("docentes", JSON.stringify(docentes));
    
        fetch('/asignaturas/asignacion/docentes/fetch', {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            console.log(data)
            if (data) {
                if (data.success) {
                    // generateRegisters();
                    // $("#contenido_datos").load(location.href+" #contenido_datos>*","");
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

    buscador = document.getElementById('searchDocentes').value = ""
    document.getElementById('containerasign').style.display = "none"
    document.getElementById('spinsearch').style.display = "none"
}
function generateAsign(element, asignados){
    var nombres = (element.apellido_paterno == null ? '' : (element.apellido_paterno).toUpperCase())+' '+(element.apellido_materno == null ? '' : (element.apellido_materno).toUpperCase())+' '+(element.primer_nombre == null ? '' : (element.primer_nombre).toUpperCase())+' '+(element.segundo_nombre == null ? '' : (element.segundo_nombre).toUpperCase());

    var tr_general = document.createElement('tr');
        var td = document.createElement('td');
            var div_general = document.createElement('div')
                div_general.setAttribute('class', 'col-md-12 card-asign mb-2')

                var inpcheck = document.createElement('input')
                    inpcheck.setAttribute('class', 'sect-check form-control')
                    inpcheck.setAttribute('type','checkbox')
                    inpcheck.setAttribute('name','asigna[]')
                    inpcheck.setAttribute('id','checkbox_'+element.id)
                    inpcheck.setAttribute('data-clase_fk', '')
                    var asignacionfk = "";
                    asignados.forEach(asign => {
                        if(asign.docente_fk == element.id){
                            asignacionfk = asign.id;
                            inpcheck.setAttribute('checked', true)
                            inpcheck.setAttribute('disabled', true)
                            div_general.setAttribute('class', 'col-md-12 card-asign-true mb-2')
                        }
                    });                    
                var div_content = document.createElement('div')
                    div_content.setAttribute('class', 'card-asign-content')

                    var span_title = document.createElement('span')
                        span_title.setAttribute('class', 'card-asign-title text-uppercase')
                        span_title.innerText = "NOMBRE: "+ nombres

                    var span_cod = document.createElement('span')
                        span_cod.setAttribute('class', 'card-asign-desc')
                        span_cod.innerText = "CÉDULA: "+element.cedula
                div_content.append(span_title)
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
                if(asign.docente_fk == element.id){
                    div_general.append(div_content_del)
                }
            }); 

        td.append(div_general)
    tr_general.append(td)
    document.getElementById('contenido_docentes').append(tr_general);
}
$("#searchDocentes").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#contenido_docentes tr").filter(function() {
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
        fetch('/asignaturas/delete/asignacion/docentes/fetch' ,{method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then(response => response.json()).then(data => {
            if (data) {
                if (data.success) { condicion.push('success'); toastr.success(data.success); }
                if (data.error) {  condicion.push('error'); toastr.error(data.error);}
                if (data.warning) { condicion.push('error'); toastr.warning(data.warning); }
            }
        })
        .catch(error => {
            console.error('MOSTRANDO ERROR DELETE ASIGN: '+error)
            oastr.error('Algo salió mal, vuelva a intentarlo');
            document.getElementById('checkboxdelete_'+idasignacion).innerHTML = `<i class="fa fa-trash" title="Eliminar asignación"  onclick="deleteRelacionDA(${idasignacion})"></i>`
            document.getElementById('checkboxdelete_'+idasignacion).style.cursor = "pointer";
        })
        .finally(()=>{
            if(condicion[0] == "success"){
                generateDocAsign();
            }
            if(condicion[0] == "error"){
                document.getElementById('checkboxdelete_'+idasignacion).innerHTML = `<i class="fa fa-trash" title="Eliminar asignación"  onclick="deleteRelacionDA(${idasignacion})"></i>`
                document.getElementById('checkboxdelete_'+idasignacion).style.cursor = "pointer";
            }
        })
    }
}