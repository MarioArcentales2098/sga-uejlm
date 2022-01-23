generateRegisters();
function generateRegisters() {
    fetch('/asignaturas/lista/fetch')
    .then(response => response.json()).then((data) => {
        console.log(data)
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

        var td_nombre = document.createElement("td");
            td_nombre.innerText = (data.nombre);

        var td_acciones = document.createElement("td");
            var td_sec = document.createElement("div");
                td_sec.setAttribute("class", "d-flex");
                td_sec.style.gridGap = "5px";
                td_sec.style.justifyContent = "center";

                var btnEdit = document.createElement("a");
                    btnEdit.setAttribute("href", "javascript;");
                    btnEdit.setAttribute("class", "btn btn-sm btn-primary");
                    btnEdit.setAttribute("data-toggle", "modal");
                    btnEdit.setAttribute("data-target", "#editRegister");
                    btnEdit.setAttribute("data-id", data.id);
                    btnEdit.setAttribute("data-nombre", data.nombre);
                    btnEdit.setAttribute("title", "Editar registro");
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
                    btnDelete.setAttribute("data-edit_nombre", data.nombre);                    
                    btnDelete.setAttribute("title", "Eliminar registro");
                    btnDelete.innerHTML = `<i class="fa fa-trash"></i>`;

            td_sec.append(btnEdit);
            // td_sec.append(btnRoles);
            td_sec.append(btnDelete);
        td_acciones.append(td_sec);

    tr_general.append(td_num);
    tr_general.append(td_nombre);
    tr_general.append(td_acciones);

    document.getElementById("tbody_registros").append(tr_general);
}

//============================ CREAR REGISTRO
document.querySelector("#btnCreateRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("nombre");

    if ( n1 == "success"){        
        $("#btnCreateRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("nombre", document.getElementById("nombre").value );

        fetch('/asignaturas/crear/asignatura/fetch', {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
        .then((response) => response.json()).then((data) => {
            if (data) {
                if (data.success) {
                    generateRegisters();
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

        fetch("/asignaturas/editar/asignatura/fetch", {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
        .then((response) => response.json())
        .then((data) => {
            if (data) {
                if (data.success) {
                    generateRegisters();
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
        console.log(data)
        if (data) {
            if (data.success) {
                generateRegisters();
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
