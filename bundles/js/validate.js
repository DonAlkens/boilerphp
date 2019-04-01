

//@Realtime Validation proccess
function validate_fields(){
    if($(this).val() == ""){
        $(this).siblings("small").fadeIn();
        $(this).css("borderColor","red");
    }

    //if not empty but email is invalid
    else if($(this).attr("name") == "email"){

        let regex = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        var email = $(this).val();
        // Checking if email is valid
        if(!email.match(regex)){
            // If not valid
            $(this).siblings("small").html("Invalid email address").fadeIn();
            $(this).css("borderColor","red");
        }

        else {
            // if email valid
            $(this).siblings("small").fadeOut("fast").html("* required");
            $(this).css("borderColor","#ccc");
        }
    }

    // Validating phone number
    else if($(this).attr("name") == "phone"){
        let phone = $(this).val();
        var regex = /^[0-9\+]{11}$/;

        if(!phone.match(regex)) {
            $(this).siblings("small").html("Invalid phone number").fadeIn();
            $(this).css("borderColor","red");
        }

        else {
            // if phone is valid
            $(this).siblings("small").fadeOut("fast").html("* required");
            $(this).css("borderColor","#ccc");
        }
    }

    // Otherwise all
    else {
        $(this).css("borderColor","#ccc");
        $(this).siblings("small").fadeOut("fast");
    }

}
