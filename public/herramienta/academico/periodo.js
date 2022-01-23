//============================ CREAR REGISTRO
document.querySelector("#btnCreateRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("anio_inicio");
    var n2 = validateRegisterSimple("anio_fin");
    var n3 = validateRegisterSimple("estado");

    if ( n1 == "success" && n2 == "success" && n3 == "success"){        
        $("#btnCreateRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("anio_inicio", document.getElementById("anio_inicio").value );
        formData.append("anio_fin", document.getElementById("anio_fin").value );
        formData.append("estado", document.getElementById("estado").value );

        fetch('/periodo/crear/periodo/fetch', {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            if (data) {
                if (data.success) {
                    $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                    toastr.success(data.success);
                    $("#createRegister").modal("hide");
                    limpiarModalRegister();                    
                    // new simpleDatatables.DataTable("#myTable");
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
    document.getElementById("anio_inicio").value = "";
    document.getElementById("anio_fin").value = "";
    document.getElementById("anio_inicio").classList.remove('parsley-error')
    document.getElementById("anio_fin").classList.remove('parsley-error')
}
function condition(){
    //no borrar se usa en el blade
    if(document.getElementById('condicionestado').value == 1){
        document.getElementById('estado').value = 0
    }else{
        document.getElementById('estado').value = ""
    }
}

//============================ EDITAR REGISTRO
$("#editRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    limpiarModalEditRegister();
    document.getElementById("idEditRegistro_fk").value = datos.data("id");
    document.getElementById("edit_anio_inicio").value = datos.data("inicio");
    document.getElementById("edit_anio_fin").value = datos.data("fin");
    document.getElementById("edit_estado").value = datos.data("estado");
});
document.querySelector("#btnEditRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("edit_anio_inicio");
    var n2 = validateRegisterSimple("edit_anio_fin");
    var n3 = validateRegisterSimple("edit_estado");

    if (n1 == "success" && n2 == "success" && n3 == "success"){
        $("#btnEditRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("id", document.getElementById("idEditRegistro_fk").value );
        formData.append("anio_inicio", document.getElementById("edit_anio_inicio").value );
        formData.append("anio_fin", document.getElementById("edit_anio_fin").value );
        formData.append("estado", document.getElementById("edit_estado").value );

        fetch("/periodo/editar/periodo/fetch", {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
        .then((response) => response.json()).then((data) => {
            if (data) {
                if (data.success) {
                    $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                    toastr.success(data.success);
                    $("#editRegister").modal("hide");
                    limpiarModalRegister();                    
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
    document.getElementById("edit_anio_inicio").value = "";
    document.getElementById("edit_anio_inicio").classList.remove('parsley-error');
    document.getElementById("edit_anio_fin").value = "";
    document.getElementById("edit_anio_fin").classList.remove('parsley-error');
    document.getElementById("edit_estado").value = ""
    document.getElementById("edit_estado").classList.remove('parsley-error');
}

//=========================== ELIMINAR REGISTER
$("#deleteRegister").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    document.getElementById("id_delete_registro_fk").value = "";
    document.getElementById("delete-text-register").innerHTML = "";

    document.getElementById("id_delete_registro_fk").value = datos.data("id");
    document.getElementById("delete-text-register").innerHTML = datos.data("dele_nombre");    
});
document.querySelector("#btnDeleteRegister").addEventListener("click", () => {
    $("#btnDeleteRegister").attr("disabled", true).text("").append(spinner);

    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("id", document.getElementById("id_delete_registro_fk").value);

    fetch("/periodo/eliminar/periodo/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        if (data) {
            if (data.success) {
                $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                toastr.success(data.success);
                limpiarModalDeleteRegister();
                $("#deleteRegister").modal("hide");
            }
            if (data.error) {  toastr.error(data.error); }
            if (data.warning) { toastr.warning(data.warning); }
        }
    })
    .catch((error) => {console.error(error);})
    .finally(() => {
        $("#btnDeleteRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
    });
});
function limpiarModalDeleteRegister() {
    document.getElementById("id_delete_registro_fk").value = "";
    document.getElementById("delete-text-register").innerHTML = "";
}




//============================ CERRAR PARCIAL
$("#modalCerrarParcial").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    limpiarModalCerrarParcial();
    var idents = datos.data("ident").split('-');
    document.getElementById("idquimestre").value = idents[2];
    document.getElementById("idparcial").value = idents[1];
    document.getElementById("idperiodo").value = idents[0];
});
document.querySelector("#btnCerrarParcial").addEventListener("click", () => {
    $("#btnCerrarParcial").attr("disabled", true).text("").append(spinner);

    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("idparcial", document.getElementById("idparcial").value);
    formData.append("idquimestre", document.getElementById("idquimestre").value);
    formData.append("idperiodo", document.getElementById("idperiodo").value);

    fetch("/periodo/cerrar/parcial/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        if (data) {
            if (data.success) {
                $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                toastr.success(data.success);
                limpiarModalCerrarParcial();
                $("#modalCerrarParcial").modal("hide");
            }
            if (data.error) {  toastr.error(data.error); }
            if (data.warning) { toastr.warning(data.warning); }
        }
    })
    .catch((error) => {console.error(error);})
    .finally(() => {
        $("#btnCerrarParcial").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
    });
});
function limpiarModalCerrarParcial() {
    document.getElementById("idquimestre").value = ""
    document.getElementById("idparcial").value = "";
    document.getElementById("idperiodo").value = "";
}

//============================ ABRIR PARCIAL
$("#modalAbrirParcial").on("shown.bs.modal", function (event) {
    var datos = $(event.relatedTarget);
    limpiarModalCerrarParcial();
    var idents = datos.data("ident").split('-');
    document.getElementById("idquimestre_a").value = idents[2];
    document.getElementById("idparcial_a").value = idents[1];
    document.getElementById("idperiodo_a").value = idents[0];
});
document.querySelector("#btnAbrirParcial").addEventListener("click", () => {
    $("#btnAbrirParcial").attr("disabled", true).text("").append(spinner);

    let csrf = document.querySelector("input[name='_token']");
    const formData = new FormData();
    formData.append("idquimestre", document.getElementById("idquimestre_a").value);
    formData.append("idparcial", document.getElementById("idparcial_a").value);
    formData.append("idperiodo", document.getElementById("idperiodo_a").value);

    fetch("/periodo/abrir/parcial/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        if (data) {
            if (data.success) {
                $("#contenido_datos").load(location.href+" #contenido_datos>*","");
                toastr.success(data.success);
                limpiarModalAbrirParcial();
                $("#modalAbrirParcial").modal("hide");
            }
            if (data.error) {  toastr.error(data.error); }
            if (data.warning) { toastr.warning(data.warning); }
        }
    })
    .catch((error) => {console.error(error);})
    .finally(() => {
        $("#btnAbrirParcial").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
    });
});
function limpiarModalAbrirParcial() {
    document.getElementById("idquimestre_a").value = ""
    document.getElementById("idparcial_a").value = "";
    document.getElementById("idperiodo_a").value = "";
}