document.querySelector("#btnEditRegister").addEventListener("click", () => {
    var n1 = validateRegisterSimple("pnombre");
    var n2 = validateRegisterSimple("papellido");
    // var n4 = validateRegisterSimple("snombre");
    // var n5 = validateRegisterSimple("sapellido");
    var n3 = validateRegisterSimple("cedula");
    var n6 = validateRegisterSimple("correo");

    if (n1 == "success" && n2 == "success" && n3 == "success" && n6 == "success" ){
        $("#btnEditRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("id", document.getElementById("idusuario").value );
        formData.append("token", document.getElementById("tokenusuario").value );
        formData.append("primernombre", document.getElementById("pnombre").value );
        formData.append("segundonombre",document.getElementById("snombre").value);
        formData.append("primerapellido",document.getElementById("papellido").value);
        formData.append("segundoapellido",document.getElementById("sapellido").value);
        formData.append("cedula", document.getElementById("cedula").value);
        formData.append("correo", document.getElementById("correo").value);

        fetch("/perfil/usuario/editar/fetch", {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
        .then((response) => response.json())
        .then((data) => {
            if (data) {
                if (data.success){ toastr.success(data.success);}
                if (data.error){ toastr.error(data.error); }
                if (data.warning){ toastr.warning(data.warning); }
            }
        })
        .catch((error) => { console.error(error);})
        .finally(() => {
            $("#btnEditRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Guardar cambios`);
        });
    }
});


document.querySelector("#btnChangePass").addEventListener("click", () => {
    var n1 = validateRegisterSimple("old_pass");
    var n2 = validateRegisterSimple("new_pass");
    document.getElementById('alert-success').style.display = "none"
    if (n1 == "success" && n2 == "success"){
        $("#btnChangePass").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("old_pass", document.getElementById("old_pass").value );
        formData.append("new_pass", document.getElementById("new_pass").value );

        fetch("/perfil/usuario/change/password/fetch", {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
        .then((response) => response.json()).then((data) => {
            console.log(data)
            document.getElementById('alert-success').style.display = "none"
            if (data) {
                if (data.success){ 
                    toastr.success(data.success);
                    document.getElementById('alert-success').style.display = "block"      
                    setTimeout(function(){ window.location.href = '/' }, 3000);                
                }
                if (data.error){ toastr.error(data.error); document.getElementById('alert-success').style.display = "none" }
                if (data.warning){ toastr.warning(data.warning); document.getElementById('alert-success').style.display = "none" }
            }
        })
        .catch((error) => { console.error(error); document.getElementById('alert-success').style.display = "none"})
        .finally(() => {
            $("#btnChangePass").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Guardar cambios`);
        });
    }
});