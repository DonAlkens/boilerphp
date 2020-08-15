(function(){

    var step = new Step();
    step.init();

    var options = {
        display: "#GalleryBox",
        thumbnail: {
            node: "div", 
            style:"thumbnail small"
        },
        removeBtn: {
            node: "button", 
            style: "btn btn-icon btn-inverse-danger btn-rounded",
            text: "x"
        }
    }

    var imageArr = [];

    $("#Gallery").change(function(){

        imageArr = ImageViewer("#Gallery", options).images;
        if(imageArr.length != 0){
            $("#GalleryBox").removeClass("hide");
        }
    });

    // if($("#ajaxForm")){ 

        $("#ajaxForm").submit(function(event){ 
            event.preventDefault();

            // Form data Validation
           var validation = true;

           $('[validate-field="true"]').each(function(){
                $(this).css("border-color","#dcdcdc").siblings('[validation-result="true"]').remove();
               
                if($(this).val() == "") {
                    var message = $(this).attr("validation-message");
                    $(this).css("border-color","red").after('<span class="text-danger" validation-result="true">'+ message +'</span>');
                    validation = false;
                }
            });

            
            if(validation){

                let edit = true;
                if(!$(this).attr("edit")) {
                    edit = false;

                    if($("#adVImages").length > 0) {

                        if(imageArr.length == 0){
                            step.modal("error", "Please add Product Images");
                            return;
                        }
                    }
                }

                var data = new FormData(this), 
                    url = $(this).attr("action"), 
                    method = $(this).attr("method");
                
                if(imageArr.length > 1){    imageArr.forEach(image => { data.append("file[]",image);}); } 
                else if(imageArr.length == 1){  data.append("file",imageArr[0]);    }
                
                $.ajax({ url, method, data, cache: false, contentType: false, processData: false, 
                    // beforeSend: function(loader) {
                            
                    // },
                    success: function(res){
                        console.log(res);

                        if(res !== null && JSON.parse(res)) {
                            res = JSON.parse(res); imageArr = [];

                            if(res.success) {

                                if(!edit) {
                                    $("input, textarea").val("");
                                    $("#img-preview").html("");
                                }

                                step.modal("success", res.message);
    
                            } 
                            else if(res.error) {

                                step.modal("error", res.error.message);
                            }
    
                            return;
                        }
                    }
                });

            } else {
                stepModal("error", "Some required fields are empty. Please check and fill all required fields before submitting the form.");
            }
        });
    // }

    $('[api-options]').each(function(){
        let select = $(this); url = $(this).attr("api-options");
        $.ajax({url, method:"GET", data: {}, success: function(list){
            if(list != null && JSON.parse(list)) {
                list = JSON.parse(list);
                list.forEach(option => {
                    let item = "<option value='" + option.value + "'>"+ option.name +"</option>";
                    select.append(item);
                });
            }
        }});
    });

})();