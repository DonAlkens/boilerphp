// ============================================================== 
    // Auth And Registration Validation script
    // ============================================================== 

    $(".signup").click(function(e){
        var agree = $("#agree");
        
         var i = 0;
        $(".form-control").each(function(){
            $(this).css("border-color","#dcdcdc").siblings(".text-danger").html("");
    
            if($(this).val() == "" && $(this).attr("req") == "true") {
                e.preventDefault();
                $(this).css("border-color","red").siblings(".text-danger").html("this field is required");
                i++;
            }
        });
    
        var email = $("#email"),
            password = $("#password"), 
            conf_password = $("#cpassword");
    
        if(Boolean(email.val())) {
            let regex = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    
            if(!email.val().match(regex)) {
                e.preventDefault();
                email.siblings(".text-danger").html("invalid email address");
            }
        }
    
        if(Boolean(password.val()) && password.val().length < 8) {
            e.preventDefault();
            password.siblings(".text-danger").html("password to short - minimum characters should be 8");
        }
    
        if(Boolean(password.val()) && Boolean(conf_password.val())) {
            if(password.val() !== conf_password.val()) {
                e.preventDefault();
                conf_password.siblings(".text-danger").html("password not matched");
            }
        }
    
    
        if(agree.prop("checked") !== true && i == 0) {
        	e.preventDefault();
        	agree.siblings(".text-danger").html("you must agree to the terms and condition.");
        }
    });


    $(".vBtn").click(function(e){
        $(".form-control").each(function(){
            $(this).css("border-color","#dcdcdc").siblings(".text-danger").html("");
            if($(this).val() == "" && $(this).attr("req") == "true") {
                e.preventDefault();
                var cleaned = $(this).attr("id").split("_").join(" ");
                $(this).css("border-color","red").siblings(".text-danger").html(cleaned + " cannot be empty");
            }
        });
    });


    $(".signin").click(function(e){
        $(".form-control").each(function(){
            $(this).css("border-color","#dcdcdc").siblings(".text-danger").html("");
            if($(this).val() == "" && $(this).attr("req") == "true") {
                e.preventDefault();
                $(this).css("border-color","red").siblings(".text-danger").html($(this).attr("id") + " cannot be empty");
            }
        });
    });