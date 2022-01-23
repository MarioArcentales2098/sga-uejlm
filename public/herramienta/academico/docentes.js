//============================ CREAR REGISTRO
document.querySelector("#btnCreateRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("primernombre");
    var n2 = validateRegisterSimple("primerapellido");
    var n3 = validateRegisterSimple("cedula");
    // var n4 = validateRegisterSimple("segundonombre");
    // var n5 = validateRegisterSimple("segundoapellido");
    var n6 = validateRegisterSimple("correo");

    if ( n1 == "success" &&   n2 == "success" &&   n3 == "success" && n6 == "success"){        
        $("#btnCreateRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("primernombre", document.getElementById("primernombre").value );
        formData.append("segundonombre", document.getElementById("segundonombre").value);
        formData.append("primerapellido",document.getElementById("primerapellido").value );
        formData.append("segundoapellido", document.getElementById("segundoapellido").value );
        formData.append("cedula", document.getElementById("cedula").value);
        formData.append("correo", document.getElementById("correo").value);

        fetch("/usuarios/crear/nuevo/docente/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            console.log(data)
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

    document.getElementById("idEditRegistro_fk").value = datos.data("id");
    document.getElementById("edit_token").value = datos.data("token");
    document.getElementById("edit_primernombre").value = datos.data("pnombre");
    document.getElementById("edit_segundonombre").value = datos.data("snombre");
    document.getElementById("edit_primerapellido").value = datos.data("papellido");
    document.getElementById("edit_segundoapellido").value = datos.data("sapellido");
    document.getElementById("edit_cedula").value = datos.data("cedula");
    document.getElementById("edit_correo").value = datos.data("email");
});
document.querySelector("#btnEditRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("edit_primernombre");
    var n2 = validateRegisterSimple("edit_primerapellido");
    // var n4 = validateRegisterSimple("edit_segundonombre");
    // var n5 = validateRegisterSimple("edit_segundoapellido");
    var n3 = validateRegisterSimple("edit_cedula");
    var n6 = validateRegisterSimple("edit_correo");

    if (n1 == "success" && n2 == "success" && n3 == "success" && n6 == "success" ){
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

        fetch("/usuarios/editar/docente/fetch", {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
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

    document.getElementById("edit_primernombre").classList.remove('parsley-error')
    document.getElementById("edit_primerapellido").classList.remove('parsley-error')
    document.getElementById("edit_cedula").classList.remove('parsley-error')
    document.getElementById("edit_correo").classList.remove('parsley-error')
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

    fetch("/usuarios/banear/docente/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
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
    fetch("/usuarios/activar/docente/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        if (data) {
            if (data.success) {
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

    fetch("/usuarios/eliminar/docente/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        console.log(data)
        if (data) {
            if (data.success){
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