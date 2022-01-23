

//###################### CREATE REGISTER ##########################
consultaRoles()
function consultaRoles(){
    fetch("/roles/permisos/consulta/fetch").then((response) => response.json()).then((response) => {
        document.getElementById('container_roles').innerHTML = ""
        if (response) {
            if (response.length > 0) {         
                response.forEach((element) => {
                    generateRoles('container_roles', element , element.roles, null);
                });
            }
        }
    })
    .catch((error) => { console.error("MOSTRAR ERROR CONSULTA ROLES: " + error);}).finally(() => {});
}
function generateRoles(agregaren , elementos , roles, marcados){
    var contenedor = document.createElement('div');
        contenedor.setAttribute('class', `form-group ${elementos.modulo_principal == 1 ? 'col-md-12' : 'col-md-4'}`);            

        var labelMod = document.createElement('label');
            labelMod.innerText = elementos.modulo;
            if(elementos.modulo_principal == 1){
                labelMod.style.color = "#5e0aab";
                labelMod.style.fontWeight = "700";
            }else{
                labelMod.style.color = "#0c8d5d";
            }            
        
        var contain_rols = document.createElement('div');
            contain_rols.style.padding = "0px 8px"
            roles.forEach(rol => {
                var div_check = document.createElement('div');
                    div_check.setAttribute('class', 'checkbox checkbox-custom');

                    var inpt_check = document.createElement('input');
                        inpt_check.setAttribute('class', `${marcados == null ? 'ads_Checkbox' : 'ads_Checkbox_edit'}`)
                        inpt_check.setAttribute('type' , 'checkbox');
                        inpt_check.setAttribute('name', `${marcados == null ? 'roles[]' : 'rolesedit[]'}`);
                        inpt_check.setAttribute('id' , `${marcados == null ? rol.id : 'edit_'+rol.id}`);
                        inpt_check.setAttribute('data-parsley-multiple' , 'groups');
                        if(marcados != null){
                            marcados.forEach(permiso => {
                                if(permiso.permiso_nombre == rol.id){
                                    inpt_check.checked = true;
                                }
                            })
                        }
                    var inpt_label = document.createElement('label');
                        inpt_label.setAttribute('for' , `${marcados == null ? rol.id : 'edit_'+rol.id}` );
                        inpt_label.innerText = rol.text;
                    
                div_check.append(inpt_check);
                div_check.append(inpt_label);
                contain_rols.append(div_check);
            });
            
    contenedor.append(labelMod);
        contenedor.append(contain_rols);
    document.getElementById(agregaren).append(contenedor);
}
document.querySelector('#btnCreateRegister').addEventListener('click', ()=>{
    var nombre = validateRegisterSimple("nombre");
    var roles = new Array();
        $('.ads_Checkbox:checked').each(function(){
            roles.push({  'rol' : $(this).attr("id") });
        });

    if (nombre == "success" && (roles.length > 0)){
        $("#btnCreateRegister").attr("disabled", true).text("").append(spinner);
        // var roles = new Array();
        // $('.ads_Checkbox:checked').each(function(){ roles.push({  'rol' : $(this).attr("id") }); });

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append("nombre", document.getElementById("nombre").value );
        formData.append("roles", JSON.stringify(roles) );

        fetch('/roles/permisos/crear/rol/fetch', {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
        .then((response) => response.json()).then((data) => {
            console.log(data)
            if (data) {
                if (data.success){ 
                    // consultaRegister();
                    $("#mytable").load(location.href+" #mytable>*","");                    
                    toastr.success(data.success);
                    $("#createRegister").modal("hide");
                    limpiarModalRegister();        
                }
                if (data.error){ toastr.error(data.error);}
                if (data.warning){ toastr.warning(data.warning);}
            }
        })
        .catch((error) => { console.error(error);})
        .finally(() => {
            $("#btnCreateRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
        });
    }
});
function limpiarModalRegister(){
    document.getElementById('nombre').value = ""
    $('input:checkbox[name="roles[]"]').prop('checked',false);
}

//###################### EDIT REGISTER ##########################
function consultaRolesEdit(identificador, nombre){
    document.getElementById('idEditRegistro_fk').value = ""
    document.getElementById('edit_nombre').value = ""

    document.getElementById('idEditRegistro_fk').value = identificador
    document.getElementById('edit_nombre').value = nombre

    fetch('/roles/permisos/edit/consulta/fetch/'+identificador).then((response) => response.json()).then((response) => {
        document.getElementById('edit_container_roles').innerHTML = ""
        if (response) {
            if (response[0].length > 0) {  
                var marcados = response[1];       
                (response[0]).forEach((element) => {
                    generateRoles('edit_container_roles', element , element.roles, marcados);
                });
            }
        }
    })
    .catch((error) => { console.error("MOSTRAR ERROR CONSULTA ROLES EDIT: " + error);}).finally(() => {});
}
document.querySelector('#btnEditRegister').addEventListener('click', ()=>{
    var nombre = validateRegisterSimple("edit_nombre");
    var roles = new Array();
    $('.ads_Checkbox_edit:checked').each(function(){
        var porcion = $(this).attr("id").split('_');
        roles.push({'rol' : porcion[1] });
    });

    if (nombre == "success" && (roles.length > 0)){
        $("#btnEditRegister").attr("disabled", true).text("").append(spinner);

        let csrf = document.querySelector("input[name='_token']");
        const formData = new FormData();
        formData.append('nombre' , document.getElementById('edit_nombre').value)
        formData.append("rol_fk", document.getElementById("idEditRegistro_fk").value );
        formData.append("roles", JSON.stringify(roles) );

        fetch('/roles/permisos/edit/rol/fetch', {method: "POST",  headers: { "X-CSRF-TOKEN": csrf.value },  body: formData })
        .then((response) => response.json()).then((data) => {
            if (data) {
                if (data.success){ 
                    // consultaRoles()
                    $("#mytable").load(location.href+" #mytable>*","");           
                    toastr.success(data.success);
                    $("#editRegister").modal("hide");
                    limpiarModalEditRegister();        
                }
                if (data.error){ toastr.error(data.error);}
                if (data.warning){ toastr.warning(data.warning);}
            }
        })
        .catch((error) => { console.error(error);})
        .finally(() => {
            $("#btnEditRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
        });
    }
});
function limpiarModalEditRegister(){
    document.getElementById('idEditRegistro_fk').value = ""
    document.getElementById('edit_nombre').value = ""
    $('input:checkbox[name="rolesedit[]"]').prop('checked',false);
}

//#################### DELETE REGISTER #######################
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

    fetch("/roles/permisos/delete/fetch", { method: "POST", headers: { "X-CSRF-TOKEN": csrf.value }, body: formData})
    .then((response) => response.json()).then((data) => {
        console.log(data)
        if (data) {
            if (data.success) {
                // consultaRegister();
                $("#mytable").load(location.href+" #mytable>*","");
                toastr.success(data.success);
                limpiarModalDeleteRegister();
                $("#deleteRegister").modal("hide");
            }
            if (data.error) {  toastr.error(data.error); }
            if (data.warning) { toastr.warning(data.warning); }
        }
    })
    .catch(error => console.error(error))
    .finally(() => {
        $("#btnDeleteRegister").attr("disabled", false).text("").append(`<i class="fa fa-check"></i> Confirmar`);
    });
 
});
function limpiarModalDeleteRegister() {
    document.getElementById("id_delete_registro_fk").value = "";
}