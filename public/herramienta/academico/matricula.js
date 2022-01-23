//============================ CREAR REGISTRO
document.querySelector("#btnCreateRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("cedula");
    var n2 = validateRegisterSimple("periodo");
    var n3 = validateRegisterSimple("curso");
    var n4 = validateRegisterSimple("nombre");

    if ( n1 == "success" &&   n2 == "success" && n3 == "success" && n4 == "success"){        
        $("#btnCreateRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("periodo_fk", document.getElementById("periodo").value);
        formData.append("curso_fk",document.getElementById("curso").value );
        formData.append("usuario_fk", document.getElementById("usuario_fk").value);

        fetch("/matriculas/crear/registro/fetch", {method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData })
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
    document.getElementById("cedula").value = "";
    document.getElementById("nombre").value = "";
    document.getElementById("periodo").value = "";
    document.getElementById("curso").value = "";
    document.getElementById("usuario_fk").value = "";
    document.getElementById("cedula").classList.remove('parsley-error')
    document.getElementById("nombre").classList.remove('parsley-error')
    document.getElementById("periodo").classList.remove('parsley-error')
    document.getElementById("curso").classList.remove('parsley-error')
}
function consultUsuario(){
    var buscador = document.getElementById('cedula').value;
    if(document.getElementById('cedula').value != ""){
        if((document.getElementById('cedula').value).length == 10){
            $("#btnsearchcedula").attr("disabled", true).text("").append(spinner);            
            fetch('/consulta/usuario/por/cedula/'+buscador)
            .then(response => response.json()).then(data => {
                if(Object.keys(data).length > 0){
                    if(data.success){            
                        var usu = data.success            
                        var pnombre = (usu.primer_nombre == null ? '' : usu.primer_nombre); var snombre = (usu.segundo_nombre == null ? '' : usu.segundo_nombre); var papellido = (usu.apellido_paterno == null ? '' : usu.apellido_paterno);  var sapellido = (usu.apellido_materno == null ? '' : usu.apellido_materno);
                        document.getElementById('nombre').value = ( pnombre +' '+ snombre +' '+ papellido +' '+ sapellido ).toString();
                        document.getElementById('usuario_fk').value = usu.id;
    
                        document.getElementById("nombre").classList.remove('parsley-error')
                    }
                    if(data.error){toastr.error(data.error);}
                }
            })
            .catch(error => console.error(error))
            .finally(()=>{
                $("#btnsearchcedula").attr("disabled", false).text("").append(`<i class="fa fa-search"></i>`);
            });
        }else{
            toastr.warning('La cédula ingresada no es valida.');
        }
    }else{
        limpiar();
    }
}
function limpiar(){
    if(document.getElementById('cedula').value == ""){
        document.getElementById('nombre').value = "";
        document.getElementById('usuario_fk').value = "";
        document.getElementById("nombre").classList.remove('parsley-error')
    }
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

    fetch("/matriculas/eliminar/matricula/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
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