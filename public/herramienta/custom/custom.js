//====== VALIDATE REGISTRO
function validateRegisterSimple(param) {
    var n = document.getElementById(param).value;
    if (n == "") {
        document.getElementById(param).classList.add("parsley-error");
        return "error";
    } else {
        document.getElementById(param).classList.remove("parsley-error");
        return "success";
    }
}
