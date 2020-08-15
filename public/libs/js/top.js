var rni = 0;
function remover(object) {
    var skeleton = $(object).parent(".row").parent(".col-md-6");
    if(rni > 0) { skeleton.remove(); rni = rni - 1;}
}

function handleblur(object) {
    if($(object).val() != null) {
        $(object).parent(".col-md-10").siblings(".rmVar").removeClass("hide").fadeIn();
    }
}