
function api_edit(object) {
    
    let connector = $(object).attr("api-edit");
    let url = $(object).attr("href");
    target_id = $(object).attr("api-item-id");

    $.ajax({
        url, method:"GET", data:{}, success: function(response) {
            //console.log(response);

            if (response !== null && JSON.parse(response)) {
                response = JSON.parse(response);

                $('[api-edit-role="'+ connector +'"]').each(function(){
                    let key;

                    if($(this).attr("api-field-key")) { 
                        key = $(this).attr("api-field-key"); 
                        if(key.indexOf(".") > -1) {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];
                            $(this).val(response[main_key][value]);
                        } 
                        else {
                            $(this).val(response[key]);
                        }
                    }

                    if($(this).attr("api-text-key")){
                        key = $(this).attr("api-text-key");
                        if(key.indexOf(".") > -1) {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];
                            $(this).html(response[main_key][value]);
                        } 
                        else {
                            $(this).html(response[key]);
                        }
                    }

                    if($(this).attr("api-selected-key")) {
                        key = $(this).attr("api-selected-key");
                        console.log(response[key].id); 
                        if(response[key].id != 0) {
                            console.log("Yes");
                            $(this).children('[value="'+ response[key].id +'"]').remove();
                            $(this).prepend('<option value="' + response[key].id + '" selected>' +  response[key].name + '</option>');
                        }
                    }

                    if($(this).attr("api-checked-key")) {
                        key = $(this).attr("api-checked-key");
                        if(key.indexOf(".") > -1) 
                        {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];
                            if(response[main_key][value] != 0) {
                                $(this).prop('checked', true);
                            } else {
                                $(this).prop('checked', false);
                            }
                        } 
                        else 
                        {
                            if(response[key] != 0) {
                                $(this).prop('checked', true);
                            } else {
                                $(this).prop('checked', false);
                            }
                        }
                        
                    }

                    if($(this).attr("api-src-key"))
                    {
                        key = $(this).attr("api-src-key");
                        prefix = "";

                        if($(this).attr("api-src-prefix")) {
                            prefix = $(this).attr("api-src-prefix");
                        }

                        if(key.indexOf(".") > -1) 
                        {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];

                            $(this).attr("src", prefix+response[main_key][value]);
                        } 
                        else 
                        {
                            $(this).attr("src", prefix+response[key]);
                        }
                    }

                });
            }
        }
    });

}

function api_view(object) {
    
    let connector = $(object).attr("api-view");
    let url = $(object).attr("href");

    $.ajax({
        url, method:"GET", data:{}, success: function(response) {
            //console.log(response);

            if (response !== null && JSON.parse(response)) {
                response = JSON.parse(response);

                $('[api-view-role="'+ connector +'"]').each(function(){
                    let key;

                    if($(this).attr("api-field-key")) { 
                        key = $(this).attr("api-field-key"); 
                        if(key.indexOf(".") > -1) {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];
                            $(this).val(response[main_key][value]);
                        } 
                        else {
                            $(this).val(response[key]);
                        }
                    }

                    if($(this).attr("api-text-key")){
                        key = $(this).attr("api-text-key");
                        if(key.indexOf(".") > -1) {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];
                            $(this).html(response[main_key][value]);
                        } 
                        else {
                            $(this).html(response[key]);
                        }
                    }

                    if($(this).attr("api-src-key"))
                    {
                        key = $(this).attr("api-src-key");
                        prefix = "";

                        if($(this).attr("api-src-prefix")) {
                            prefix = $(this).attr("api-src-prefix");
                        }

                        if(key.indexOf(".") > -1) 
                        {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];

                            $(this).attr("src", prefix+response[main_key][value]);
                        } 
                        else 
                        {
                            $(this).attr("src", prefix+response[key]);
                        }
                    }

                });
            }
        }
    });

}

function api_delete(object) {

    let connector = $(object).attr("api-delete");
    let url = $(object).attr("href");

    target_id = $(object).attr("api-item-id");

    $.ajax({
        url, method:"GET", data:{}, success: function(response) {
            //console.log(response);

            if (response !== null && JSON.parse(response)) {
                response = JSON.parse(response);

                $('[api-delete-role="'+ connector +'"]').each(function(){
                    let key;
                    
                    if($(this).attr("api-field-key")) { 
                        key = $(this).attr("api-field-key"); 
                        if(key.indexOf(".") > -1) {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];
                            $(this).val(response[main_key][value]);
                        } 
                        else {
                            $(this).val(response[key]);
                        }
                    }

                    if($(this).attr("api-text-key")){
                        key = $(this).attr("api-text-key");
                        if(key.indexOf(".") > -1) {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];
                            $(this).html(response[main_key][value]);
                        } 
                        else {
                            $(this).html(response[key]);
                        }
                    }

                    if($(this).attr("api-src-key"))
                    {
                        key = $(this).attr("api-src-key");
                        prefix = "";

                        if($(this).attr("api-src-prefix")) {
                            prefix = $(this).attr("api-src-prefix");
                        }

                        if(key.indexOf(".") > -1) 
                        {
                            keys = key.split("."); main_key = keys[0]; value = keys[1];

                            $(this).attr("src", prefix+response[main_key][value]);
                        } 
                        else 
                        {
                            $(this).attr("src", prefix+response[key]);
                        }
                    }

                });
            }
        }
    });

}

function updateStock(object) {

    var stock = 1;
    var product = $(object).attr("item-id");
    if($(object).prop("checked") == true) {
        stock = 0;
    }

    var url = "/api/a/product/update-stock";
    $.ajax({url, method: "POST", data: {stock, product}, 
        success:function(response) {
            if (response !== null && JSON.parse(response)) {
                        
                response = JSON.parse(response);
                if (response.success) 
                {
                    var message = '<div class="toast-notification success">\
                        <h6 class="text-white"><b>Successfull!</b></h6><p>'+ response.message + '</p>\
                        <span class="tos-close flaticon-circle text-white"><span>\
                    </div>';
                } else 
                {
                    var message = '<div class="toast-notification error">\
                        <h6 class="text-white"><b>Error Occured!</b></h6><p>'+ response.error.message + '</p>\
                        <span class="tos-close flaticon-circle text-white"><span>\
                    </div>';
                }
                
                $("body").append(message);
                $(".tos-close").click(function(){ $(".toast-notification").remove(); });
                $(".toast-notification").fadeOut(6000);
            }
        }
    });

}

function print_open(url) {
    window.open(url);
}