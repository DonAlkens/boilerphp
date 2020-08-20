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

    $("#Image").change(function(){
        options2 = {
            display: "#mainImage",
            thumbnail: {
                node: "div", 
                style:""
            },
            removeBtn: {
                node: "", 
                style: "",
                text: ""
            },
            showRemoveBtn: false,
            clearInitial: true,
        }
        ImageViewer("#Image", options2);
    });


    $("#ajaxForm").submit(function(event){ 
        event.preventDefault();
        var form = $(this);
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
            if(!form.attr("edit") && form.attr("enctype") == "multipart/form-data") {
                edit = false;

                if(imageArr.length == 0){
                    step.modal("error", "Please add Product Images");
                    return;
                }
            }

            var data = new FormData(this), 
                url = form.attr("action"), 
                method = form.attr("method");

            if(form.attr("class") == "add-product") {
                step.loader(true, "Saving Products...");
            }
            
            if(imageArr.length > 0){    imageArr.forEach(image => { data.append("gallery[]",image);}); } 
            
            $.ajax({ url, method, data, cache: false, contentType: false, processData: false, 
                beforeSend: function() {
                    
                },
                success: function(res){
                    console.log(res);

                    if(res !== null && JSON.parse(res)) {
                        res = JSON.parse(res);
                        step.loader(false);

                        if(res.success) {

                            if(!edit && form.attr("class") != "add-product") {
                                imageArr = [];
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
            step.modal("error", "Some required fields are empty. Please check and fill all required fields before submitting the form.");
        }
    });

    $('[api-options]').each(function(){
        let select = $(this); url = $(this).attr("api-options");
        $.ajax({url, method:"GET", data: {}, success: function(list){
            console.log(list);
            if(list != null && JSON.parse(list)) {
                list = JSON.parse(list);
                list.forEach(option => {
                    let item = "<option value='" + option.value + "'>"+ option.name +"</option>";
                    select.append(item);
                });
            }
            else {
                console.error(list);
            }
        }});
    });

    $('[api-table]').each(function(){
        let table = $(this); url = $(this).attr("api-table");
        $.ajax({url, method: "GET", data:{}, success:function(rows){ 
            console.log(rows);
            if(rows != null && JSON.parse(rows)) {
                rows = JSON.parse(rows);
                rows.forEach(row => {
                    let item = "<tr>";
                    row.forEach(td => { item += "<td>" + td + "</td>"; });
                    item += "<td class='actions'>"+"</td>";
                    item += "</tr>";
                    table.append(item);
                });
            }
            else {
                console.error(rows);
            }
        }});
    });

    $('[api-change]').change(function(){
        let url = $(this).attr("api-change") + "/" + $(this).val();
        let object = $(this).attr("api-result-target"), select = $(object);

        $.ajax({url, method:"GET", data:{}, success:function(list) { console.log(list);
            if(list != null && JSON.parse(list)) {
                select.removeAttr("disabled");
                list = JSON.parse(list);

                
                if(list.length == 0) {
                    select.html("");
                    select.attr("disabled", "disabled");
                    return;
                }

                let first_item = '<option value="">**select a sub category**</option>';
                select.append(first_item);

                list.forEach(option => {
                    let item = "<option value='" + option.value + "'>"+ option.name +"</option>";
                    select.append(item);
                });
                
            }
            else {
                console.error(list);
            }
        }})
    });

    setTimeout(function(){$(".table").DataTable()}, 1000);

})();