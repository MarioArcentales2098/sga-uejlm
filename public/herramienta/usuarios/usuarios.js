// consultaUsuarios();
function consultaUsuarios() {
    fetch("/usuarios/lista/usuarios/fetch").then((response) => response.json()).then((data) => {
        if (data) {
            if (data.length > 0) {
                document.getElementById("tbody_registros").innerHTML = "";
                var contador = 1;
                data.forEach((element) => {
                    generateRows(element, contador);
                    contador++;
                });
            }
        }
    })
    .catch((error) => {
        console.error("MOSTRAR ERROR GENERAR ROWS :" + error);
    })
    .finally(() => {});
}
function generateRows(data, contador) {
    var tr_general = document.createElement("tr");

        var td_num = document.createElement("td");
            td_num.innerText = contador;

        var td_cedula = document.createElement("td");
            td_cedula.innerText = data.cedula == null || data.cedula == "" ? "--" : data.cedula;

        var td_nombres = document.createElement("td");
            td_nombres.innerText = (data.primer_nombre == null || data.primer_nombre == "" ? "--" : data.primer_nombre) + " " +  (data.segundo_nombre == null || data.segundo_nombre == ""  ? "--" : data.segundo_nombre);

        var td_apellidos = document.createElement("td");
            td_apellidos.innerText = (data.apellido_paterno == null || data.apellido_paterno == "" ? "--" : data.apellido_paterno) + " " + (data.apellido_materno == null || data.apellido_materno == "" ? "--" : data.apellido_materno);

        var td_correo = document.createElement("td");
            td_correo.innerText =  data.email == null || data.email == "" ? "--" : data.email;

        // var td_tipo = document.createElement("td");
        // if(data.tipo_usuario = )
        // td_tipo.innerText =  data.email == null || data.email == "" ? "--" : data.email;

        var td_acciones = document.createElement("td");
            var td_sec = document.createElement("div");
                td_sec.setAttribute("class", "d-flex");
                td_sec.style.gridGap = "5px";

                var btnEdit = document.createElement("a");
                    btnEdit.setAttribute("href", "javascript;");
                    btnEdit.setAttribute("class", "btn btn-sm btn-primary");
                    btnEdit.setAttribute("data-toggle", "modal");
                    btnEdit.setAttribute("data-target", "#editRegister");
                    btnEdit.setAttribute("data-id", data.id);
                    btnEdit.setAttribute("data-token", data.token);
                    btnEdit.setAttribute("data-pnombre", data.primer_nombre);
                    btnEdit.setAttribute("data-snombre", data.segundo_nombre);
                    btnEdit.setAttribute("data-papellido", data.apellido_paterno);
                    btnEdit.setAttribute("data-sapellido", data.apellido_materno);
                    btnEdit.setAttribute("data-cedula", data.cedula);
                    btnEdit.setAttribute("data-email", data.email);
                    btnEdit.setAttribute("title", "Editar usuario");
                    btnEdit.innerHTML = `<i class="fa fa-pencil"></i>`;

                var btnRoles = document.createElement("a");
                    btnRoles.setAttribute("href", "javascript;");
                    btnRoles.setAttribute("class", "btn btn-sm btn-info");
                    btnRoles.setAttribute("title", "Roles y permisos");
                    btnRoles.innerHTML = `<i class="fa fa-black-tie"></i>`;

                var btnDelete = document.createElement("a");
                    btnDelete.setAttribute("href", "javascript;");
                    btnDelete.setAttribute("class", "btn btn-sm btn-danger");
                    btnDelete.setAttribute("data-toggle", "modal");
                    btnDelete.setAttribute("data-target", "#deleteRegister");
                    btnDelete.setAttribute("data-id", data.id);
                    btnDelete.setAttribute("title", "Eliminar usuario");
                    btnDelete.innerHTML = `<i class="fa fa-trash"></i>`;

                var btnBan = document.createElement("a");
                    btnBan.setAttribute("href", "javascript;");
                    btnBan.setAttribute("class", "btn btn-sm btn-warning");
                    btnBan.setAttribute("data-toggle", "modal");
                    btnBan.setAttribute("data-target", "#banearRegister");
                    btnBan.setAttribute("data-id", data.id);
                    btnBan.setAttribute("title", "Deshabilitar usuario");
                    btnBan.innerHTML = `<i class="fa fa-ban"></i>`;

                var btnActive = document.createElement("a");
                    btnActive.setAttribute("href", "javascript;");
                    btnActive.setAttribute("class", "btn btn-sm btn-success");
                    btnActive.setAttribute("data-toggle", "modal");
                    btnActive.setAttribute("data-target", "#activeRegister");
                    btnActive.setAttribute("data-id", data.id);
                    btnActive.setAttribute("title", "Habilitar usuario");
                    btnActive.innerHTML = `<i class="fa fa-undo"></i>`;

            td_sec.append(btnEdit);
            // td_sec.append(btnRoles);
            if(data.estado == 1){ td_sec.append(btnBan);}
            if(data.estado == 0){ td_sec.append(btnActive);}
            td_sec.append(btnDelete);
        td_acciones.append(td_sec);

    tr_general.append(td_num);
    tr_general.append(td_cedula);
    tr_general.append(td_nombres);
    tr_general.append(td_apellidos);
    tr_general.append(td_correo);
    tr_general.append(td_acciones);

    document.getElementById("tbody_registros").append(tr_general);
}

//============================ CREAR REGISTRO
document.querySelector("#btnCreateRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("primernombre");
    var n2 = validateRegisterSimple("primerapellido");
    var n3 = validateRegisterSimple("cedula");
    // var n4 = validateRegisterSimple("segundonombre");
    // var n5 = validateRegisterSimple("segundoapellido");
    var n6 = validateRegisterSimple("correo");
    var n7 = validateRegisterSimple("tipo_usuario");

    if ( n1 == "success" &&   n2 == "success" &&   n3 == "success" && n6 == "success" && n7 == "success"){        
        $("#btnCreateRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("primernombre", document.getElementById("primernombre").value );
        formData.append("segundonombre", document.getElementById("segundonombre").value);
        formData.append("primerapellido",document.getElementById("primerapellido").value );
        formData.append("segundoapellido", document.getElementById("segundoapellido").value );
        formData.append("cedula", document.getElementById("cedula").value);
        formData.append("correo", document.getElementById("correo").value);
        formData.append("tipo_usuario", document.getElementById("tipo_usuario").value);

        fetch("/usuarios/crear/nuevo/usuario/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            if (data) {
                if (data.success) {
                    // consultaUsuarios();
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
    document.getElementById("primernombre").value = "";
    document.getElementById("primerapellido").value = "";
    document.getElementById("segundonombre").value = "";
    document.getElementById("segundoapellido").value = "";
    document.getElementById("cedula").value = "";
    document.getElementById("correo").value = "";

    document.getElementById("primernombre").classList.remove('parsley-error')
    document.getElementById("primerapellido").classList.remove('parsley-error')
    // document.getElementById("segundonombre").classList.remove('parsley-error')
    // document.getElementById("segundoapellido").classList.remove('parsley-error')
    document.getElementById("cedula").classList.remove('parsley-error')
    document.getElementById("correo").classList.remove('parsley-error')
}

//============================ EDITAR REGISTRO
$("#editRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    document.getElementById("idEditRegistro_fk").value = "";
    document.getElementById("edit_token").value = "";
    document.getElementById("edit_primernombre").value = "";
    document.getElementById("edit_primerapellido").value = "";
    document.getElementById("edit_segundonombre").value = "";
    document.getElementById("edit_segundoapellido").value = "";
    document.getElementById("edit_cedula").value = "";
    document.getElementById("edit_correo").value = "";
    document.getElementById("edit_tipo_usuario").value = "";

    document.getElementById("idEditRegistro_fk").value = datos.data("id");
    document.getElementById("edit_token").value = datos.data("token");
    document.getElementById("edit_primernombre").value = datos.data("pnombre");
    document.getElementById("edit_segundonombre").value = datos.data("snombre");
    document.getElementById("edit_primerapellido").value = datos.data("papellido");
    document.getElementById("edit_segundoapellido").value = datos.data("sapellido");
    document.getElementById("edit_cedula").value = datos.data("cedula");
    document.getElementById("edit_correo").value = datos.data("email");
    document.getElementById("edit_tipo_usuario").value = datos.data("tipo");
});
document.querySelector("#btnEditRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("edit_primernombre");
    var n2 = validateRegisterSimple("edit_primerapellido");
    // var n4 = validateRegisterSimple("edit_segundonombre");
    // var n5 = validateRegisterSimple("edit_segundoapellido");
    var n3 = validateRegisterSimple("edit_cedula");
    var n6 = validateRegisterSimple("edit_correo");
    var n6 = validateRegisterSimple("edit_correo");
    var n7= validateRegisterSimple("edit_tipo_usuario");

    if (n1 == "success" && n2 == "success" && n3 == "success" && n6 == "success" && n7 == "success"){
        $("#btnEditRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("id", document.getElementById("idEditRegistro_fk").value );
        formData.append("token", document.getElementById("edit_token").value );

        formData.append("primernombre", document.getElementById("edit_primernombre").value );
        formData.append("segundonombre",document.getElementById("edit_segundonombre").value);
        formData.append("primerapellido",document.getElementById("edit_primerapellido").value);
        formData.append("segundoapellido",document.getElementById("edit_segundoapellido").value);
        formData.append("cedula", document.getElementById("edit_cedula").value);
        formData.append("correo", document.getElementById("edit_correo").value);
        formData.append("tipo_usuario", document.getElementById("edit_tipo_usuario").value);

        fetch("/usuarios/editar/usuario/fetch", {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
        .then((response) => response.json())
        .then((data) => {
            if (data) {
                if (data.success) {
                    toastr.success(data.success);
                    // consultaUsuarios();
                    $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                    $('#editRegister').modal('hide');
                    limpiarModalEditRegister();
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
    document.getElementById("edit_primernombre").value = "";
    document.getElementById("edit_primerapellido").value = "";
    document.getElementById("edit_segundonombre").value = "";
    document.getElementById("edit_segundoapellido").value = "";
    document.getElementById("edit_cedula").value = "";
    document.getElementById("edit_correo").value = "";
    document.getElementById("edit_tipo_usuario").value = "";

    document.getElementById("edit_primernombre").classList.remove('parsley-error')
    document.getElementById("edit_primerapellido").classList.remove('parsley-error')
    // document.getElementById("edit_segundonombre").classList.remove('parsley-error')
    // document.getElementById("edit_segundoapellido").classList.remove('parsley-error')
    document.getElementById("edit_cedula").classList.remove('parsley-error')
    document.getElementById("edit_correo").classList.remove('parsley-error')
    document.getElementById("edit_tipo_usuario").classList.remove('parsley-error')
}
//=========================== BANEAR USUARIO
$("#banearRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    document.getElementById("id_ban_registro_fk").value = "";
    document.getElementById("id_ban_registro_fk").value = datos.data("id");
});
document.querySelector("#btnBanRegister").addEventListener("click", () => {
    $("#btnBanRegister").attr("disabled", true).text("").append(spinner);

    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("id", document.getElementById("id_ban_registro_fk").value);

    fetch("/usuarios/banear/usuario/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        console.log(data)
        if (data) {
            if (data.success) {
                // consultaUsuarios();
                $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                toastr.success(data.success);
                $("#banearRegister").modal("hide");
                limpiarModalBanRegister();
            }
            if (data.error) {  toastr.error(data.error); }
            if (data.warning) { toastr.warning(data.warning); }
        }
    })
    .catch((error) => {
        console.error(error);
    })
    .finally(() => {
        $("#btnBanRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
    });
 
});
function limpiarModalBanRegister() {
    document.getElementById("id_ban_registro_fk").value = "";
}
//=========================== ACTIVAR USUARIO
$("#activeRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    document.getElementById("id_act_registro_fk").value = "";
    document.getElementById("id_act_registro_fk").value = datos.data("id");
});
document.querySelector("#btnActiveRegister").addEventListener("click", () => {
    $("#btnActiveRegister").attr("disabled", true).text("").append(spinner);

    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("id", document.getElementById("id_act_registro_fk").value);
    fetch("/usuarios/activar/usuario/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        console.log(data)
        if (data) {
            if (data.success) {
                // consultaUsuarios();
                $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                toastr.success(data.success);
                $("#activeRegister").modal("hide");
                limpiarModalBanRegister();
            }
            if (data.error) {  toastr.error(data.error); }
            if (data.warning) { toastr.warning(data.warning); }
        }
    })
    .catch((error) => {
        console.error(error);
    })
    .finally(() => {
        $("#btnActiveRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
    });
 
});
function limpiarModalActiveRegister() {
    document.getElementById("id_act_registro_fk").value = "";
}
//=========================== ELIMINAR REGISTER
$("#deleteRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    document.getElementById("id_delete_registro_fk").value = "";
    document.getElementById("id_delete_registro_fk").value = datos.data("id");
});
document.querySelector("#btnDeleteRegister").addEventListener("click", () => {
    $("#btnDeleteRegister").attr("disabled", true).text("").append(spinner);

    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("id", document.getElementById("id_delete_registro_fk").value);

    fetch("/usuarios/eliminar/usuario/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        console.log(data)
        if (data) {
            if (data.success) {
                // consultaUsuarios();
                $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                toastr.success(data.success);
                $("#deleteRegister").modal("hide");
                limpiarModalDeleteRegister();
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
