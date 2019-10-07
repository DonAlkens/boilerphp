(function(){

    var imageArr = [];

    $("#adVImages").change(function(e){
        var file = document.querySelector("#adVImages");
        var length = file.files.length;

        if(imageArr.length > 5){
            alert("Maximum picture at a time is 5");
            return;
        }

        else if(length > 5) {
            alert("Maximum picture at a time is 5");
            return;
        }

        for(var i = 0; i < length; i++) {
            var filename = file.files[i].name;
            var ext = filename.split(".").pop().toLowerCase();
            if(jQuery.inArray(ext,["gif","png","jpeg","jpg"]) == -1)
            {
                file.value = "";
                alert("Invalid file selected!");
            } else {
                imageArr.push(file.files[i]);

                const reader = new FileReader(); // The file reader object
                reader.onload = function(){
                    //When files have been read by the file reader
                    //the onload method will be executed - async
                
                    //For an image reading and previewing
                    //Create a new image object
                    const image = new Image();
                    image.src = reader.result;
                    //Render image to browser
                    image.setAttribute("id",imageArr.length - 1);
                    var imgbox = document.createElement("div");
                    imgbox.setAttribute("class","img-thumbnail");
                    imgbox.appendChild(image);
                
                    document.getElementById("img-preview").appendChild(imgbox);
                }
                //Browser file reader object
                // It can read files as text using the the readAsText method from the FileReader object.
                //It can read files as URL link using the readAsDataURL method
                reader.readAsDataURL(file.files[i]);
            }
        }
        
    });


    if($("#ajaxForm")){ 
        $("#ajaxForm").submit(function(event){ 
            event.preventDefault();
            
            var data = new FormData(this);
            var url = $(this).attr("action");
            var method = $(this).attr("method");
            
            if(imageArr.length > 1){
                for (let i = 0; i < imageArr.length; i++) {
                    data.append("file[]",imageArr[i]);
                }
            } else{
                data.append("file",imageArr[0]);
            }
             
                
                 
        $.ajax({ url, method, data, cache: false, contentType: false, processData: false, 
            beforeSend: function(loader) {
                    $("button").attr("disabled","disabled");
                    loader = '<i class="fas fa-spin fa-spinner"></i>';
                    $("#ajaxStatus").html(loader);
                },
                success: function(res){
                    console.log(res);
                    if(res !== null && JSON.parse(res)){
                        res = JSON.parse(res);
                        $("button").removeAttr("disabled");
                        if(!res.error){
                            $("#ajaxStatus").html('<span class="alert alert-success" role="alert">'+ res.message +'</span>');
                            window.location.href = window.location.href;
                        } else if(res.error){
                           
                           $("#ajaxStatus").html('<span class="alert alert-danger" role="alert">'+ res.error.message +'</span>');
                        }
                        $(".alert").fadeOut(6000);
                        return;
                        
                    }
                }
            });
        });
    }

    if($("#ajaxCategory")){
        $("#ajaxCategory").change(function(){
            var id = $(this).val();
            $.ajax({
                url: "/store/res/get-subcats",
                method: "GET",
                data: {"cat_id":id},
                beforeSend: function(loader){
                    loader = '<i class="fas fa-spin fa-spinner"></i>';
                    $(".subcats-main").html(loader);
                },
                success: function(res){
                    if(res !== null && JSON.parse(res)){
                       var data = JSON.parse(res);
                       
                       var subForm = '<label class="col-form-label">Select Sub Category</label>';
                       subForm += '<select class="form-control" name="productSubCategoryID">';
                       subForm += '<option selected diabled value="">Choose Sub category</option>';
                       for(var i = 0; i < data.length; i++){
                        subForm += '<option value="'+ data[i].category_id+'">'+ data[i].category_name +'</option>';
                       }
                       subForm += '</select>';
                       $(".subcats-main").html(subForm);
                       return;
                    }
                    $(".subcats-main").html("");
                }
            }); 
        });
    }

    var link_d = $("#link-d");
    $(".sC").change(function(){
        link_d.html("/"+$(this).val());
    });
    $("#ssC").change(function(){
        let f = link_d.html().split("/")[1];
        link_d.html("/"+f+"/"+$(this).val());
    });

    if($(".subcats")){
        $(".subcats").hide();
    }

    if($("#ajaxCategoryForSlide")){
        $("#ajaxCategoryForSlide").change(function(e){
            var cat_name = $(this).val();
            $.ajax({
                url: "/store/res/get-subcats-with-name",
                method: "GET",
                data: {"cat_name":cat_name},
                beforeSend: function(loader){
                    $(".subcats").show();
                    // loader = '<i class="fas fa-spin fa-spinner"></i>';
                    // $(".subcats").html(loader);
                },
                success: function(res){
                    
                    if(res !== null && JSON.parse(res)){
                       var data = JSON.parse(res);
                       var subForm = '<option selected diabled value="">Choose Sub category</option>';
                       for(var i = 0; i < data.length; i++){
                        subForm += '<option value="'+ data[i].category_name+'">'+ data[i].category_name +'</option>';
                       }
                       $("#ssC").html(subForm);
                       return;
                    }
                    $("#ssC").html("");
                }
            }); 
        });
    }

    $("#Store").keyup(function(){
        var store_name = $(this).val();
        var url = $(this).parent("div").parent("form").attr("action");
        $.ajax({ url, method: "POST", data: {"store_name":store_name,"ajax":true},
            beforeSend: function(loader){
                $("button").attr("disabled","disabled");
                loader = '<i class="fas fa-spin fa-spinner"></i>';
                $("#ajaxStatus").css("text-align","center").html(loader);
            },
            success: function(res){
                if(res !== null && JSON.parse(res)){
                    res = JSON.parse(res);
                    if(!res.error){
                        $("button").removeAttr("disabled");
                        $("#ajaxStatus").html('<span class="text-success"><i class="fas fa-check"></i>&nbsp; '+ res.message +'</span>');
                    } else if(res.error){
                        $("#ajaxStatus").html('<span class="text-danger"><i class="fas fa-times"></i>&nbsp;'+ res.error.message +'</span>');
                    }
                }
            }
        });
    });

    $(".hyper").on("click", function(){ 
        var id = $(this).attr("ref");
        var url = $(this).parent("div").attr("url");
        var method = $(this).parent("div").attr("method");

        $(".editSub").hide();
        if($(this).text() == "View"){
            $.ajax({
                url: url,
                method: method,
                data:{pc_id: id},
                success: function(ret){ 
                    if(ret !="" && JSON.parse(ret)){
                        var output = JSON.parse(ret);
                        $("#exampleModalLabel").html("PRODUCT PREVIEW");
                      
                        if(output.productID == id){
                            $("#productMDiv").html("<table width='100%'><tr><td style='width:30%;'><div style='padding:3px; border:1px solid gray; border-radius:5px;'><img src='/resources/product_images/"+output.productImage+"' style='width:100%;'></div></td><td style='padding-left:15px;'><div>Product Brand: "+output.productBrand+"</div><div>Product Title: "+output.productTitle+"</div><div>Product SKU: "+output.ProductSKU+"</div><div>product Price: "+output.productPrice+"</div></td></tr></table>");
                        }else{
                        $("#productMDiv").html("<div>Category Name: "+output.category_name+"</div><div>Category ID: "+output.category_id+"</div><div>Description: "+output.category_description+"</div><div>Creation Date: "+output.created_date+"</div>");
                            }
                }
                }
            });

        } else if($(this).text() == "Edit"){
        // 
            $.ajax({
                url: url,
                method: method,
                data:{pc_id: id},
                success: function(ret){
                    if(ret !="" && JSON.parse(ret)){
                    
                        var output = JSON.parse(ret);
                        $("#editcat").html("Edit product category");
                
                        $("#category_name").val(output.category_name);
                        $("#summernote").summernote("code",output.category_description);
                    
                        $("#category_id").val(id);
                        $(".ajaxBtn").html("Update Category");
                        $(".collapsed").trigger("click");

                        $("#category_name").focus();
                    }
                }
            });
        
        } else if($(this).text() == "Delete"){ 
            if(confirm("Are you sure, You want to delete this Category ? ")){
                $.ajax({
                    url: url,
                    method: method,
                    data:{del_category_id: id},
                    success: function(ret){
                        window.location.href=""+url;
                    }
                });
            }
        }

    });

    $(".editSub").hide();
 
    $(".sub-hyper").on("click", function(){
    var id = $(this).attr("ref");
    $(".editSub").show();
        $.ajax({
            url: "/store/product-category/actions",
            method: "POST",
            data:{sub_cat_id: id},
            success: function(ret){
                if(ret !="" && JSON.parse(ret)){
                
                    var output = JSON.parse(ret);
                    $("#exampleModalLabel").html("PRODUCT SUB-CATEGORY PREVIEW");
                    $("#productMDiv").html("<div>Sub-Category Name: "+output.category_name+"</div><div>Sub-Category Property: "+output.properties+"</div><div>Sub-Description: "+output.category_description+"</div><div>Creation Date: "+output.created_date+"</div>");
                    $(".editSub").attr("ref",output.category_id);
                }
            }
        });
    });

    $(".editSub").on("click", function(){
        var id = $(this).attr("ref");
        $.ajax({
            url: "/store/product-category/actions",
            method: "POST",
            data:{edit_sub_catigory: id},
            success: function(ret){
                if(ret !="" && JSON.parse(ret)){
                    var output = JSON.parse(ret);
                    window.location.href="/store/product-category/subcategories?editCat="+output.category_id;
            
                    $.ajax({
                        url: "/store/product-category/subcategories",
                        method: "get",
                        data:{editCat: output.category_id},
                        success: function(out){
                            if(out !="" && JSON.parse(out)){
                                alert(out);
                            }
                        }
                    });
                }
            }
        });
    }); 
   

    function refresh(){

    }
})();