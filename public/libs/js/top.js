var rni = 0;
function remover(object) {
    var skeleton = $(object).parent(".row").parent(".col-md-6");
    if(rni > 0) { skeleton.remove(); rni = rni - 1;}
}

function remover2(object) {
    var skeleton = $(object).parent(".row").parent(".col-md-12");
    if(rni > 0) { skeleton.remove(); rni = rni - 1;}
}

function handleblur(object) {
    if($(object).val() != null) {
        $(object).parent(".col-md-10").siblings(".rmVar").removeClass("hide").fadeIn();
        $(object).parent(".form-group").parent(".col-md-3").siblings(".rmVar").removeClass("hide").fadeIn();
    }
}

function handleblur2(object) {
    if($(object).val() != null) {
        $(object).parent(".form-group").parent(".col-md-8").siblings(".rmVar").removeClass("hide").fadeIn();
    }
}

function removeVariation(object) {
    $(object).parent(".VRow").parent(".VItem").remove();
}