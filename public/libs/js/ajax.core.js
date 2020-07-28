(function(){

    var imageArr = [];

    $("#adVImages").change(function(e){
        var file = document.querySelector("#adVImages");
        var length = file.files.length;

        // if(imageArr.length > 5){
        //     alert("Maximum picture at a time is 5");
        //     return;
        // }

        // else if(length > 5) {
        //     alert("Maximum picture at a time is 5");
        //     return;
        // }
        var j = 0;
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
                    var imgbox = document.createElement("div");
                    imgbox.setAttribute("class","col-md-2");
                    imgbox.setAttribute("id", (j))

                    var inner = document.createElement("div");
                    inner.setAttribute("class", "img-card");

                    var overlay = document.createElement("div");
                    overlay.setAttribute("class", "img-overlay");

                    var remove = document.createElement("a");
                    remove.setAttribute("file-id", (j));
                    remove.setAttribute("class", "remove");
                    remove.setAttribute("href", "#remove");
                    remove.innerHTML = "Remove";

                    remove.onclick = function(event) {
                        var index = event.target.attributes["file-id"].nodeValue;
                        imageArr.splice(Number(index), 1);
                        $("#"+index).remove();
                    }

                    overlay.appendChild(remove);
                    inner.appendChild(overlay);
                    inner.appendChild(image);
                    imgbox.appendChild(inner);
                
                    document.getElementById("img-preview").appendChild(imgbox);
                    j++;
                }
                //Browser file reader object
                // It can read files as text using the the readAsText method from the FileReader object.
                //It can read files as URL link using the readAsDataURL method
                reader.readAsDataURL(file.files[i]);
            }
        }
        
    });


    // if($("#ajaxForm")){ 

        $("input, textarea, select").blur(function(){
            var val = $(this).val();
            if($(this).attr("req") && $(this).attr("req") == "true"){
                if(val == ""){
                    // var cleaned = $(this).attr("id").split("_").join(" ");
                    $(this).css("border-color","red");
                    // .siblings(".text-danger").html(cleaned + " cannot be empty");
                }  else {
                    $(this).css("border-color","#dcdcdc");
                    // .siblings(".text-danger").html("");
                }
            }
        });

        $("#ajaxForm").submit(function(event){ 
            event.preventDefault();

            // Form data Validation
           var validation = true;

           $(".form-control").each(function(){
                $(this).css("border-color","#dcdcdc");
                // .siblings(".text-danger").html("");
                if($(this).val() == "" && $(this).attr("req") == "true") {
                    // var cleaned = $(this).attr("name").split("_").join(" ");
                    $(this).css("border-color","red");
                    // .siblings(".text-danger").html(cleaned + " cannot be empty");
                    validation = false;
                }
            });

            
            if(validation){

                let edit = true;
                if(!$(this).attr("edit")) {
                    edit = false;
                    if($("#adVImages").length > 0) {
                        if(imageArr.length == 0){
                            $("#r-title").html("Warning!").addClass("text-warning");
                            $("#r-message").html("Please add Product Images");
                            $("#response").fadeIn();
                            return;
                        }
                    }
                }

                var data = new FormData(this);
                var url = $(this).attr("action");
                var method = $(this).attr("method");
                
                if(imageArr.length > 1){
                    for (let i = 0; i < imageArr.length; i++) {
                        data.append("file[]",imageArr[i]);
                    }
                } else if(imageArr.length == 1){
                    data.append("file",imageArr[0]);
                }
                
                $.ajax({ url, method, data, cache: false, contentType: false, processData: false, 
                    // beforeSend: function(loader) {
                    //     $("#actionBtn").attr("disabled","disabled");
                    // },
                    success: function(res){
                        console.log(res);
                        if(res !== null && JSON.parse(res)){
                            res = JSON.parse(res);
                            imageArr = [];
                            $("#actionBtn").removeAttr("disabled");
                            if(!res.error){

                                if(!edit) {
                                    $("input, textarea").val("");
                                    $("#img-preview").html("");
                                }

                                $("#r-title").html("Success!").addClass("text-success");
                                $("#r-message").html(res.message);
                                $("#response").fadeIn();

    
                            } else if(res.error){

                                $("#r-title").html("Error Occured!").addClass("text-error");
                                $("#r-message").html(res.error.message);
                                $("#response").fadeIn();
                            }
    
                            return;
                        }
                    }
                });

            } else {
                $("#r-title").html("Warning!").addClass("text-warning");
                $("#r-message").html('Some required fields are empty. Please check and fill all required fields before submitting the form.');
                $("#response").fadeIn();
            }
        });
    // }

    $(".m-close").click(function(){
        $(".cm-overlay").fadeOut();
    });


    var cartManager = () => {
        $(".addtocart, .btn-addtocart").click(function(){

            var target = $(this);

            let product = target.attr("pd");
            let quantity = 1;

            let data = { product, quantity, options: {}};

            let with_opt = target.attr("with-options");
            if(with_opt == "true") {
                quantity = $("#quantity").val();
                data.quantity = quantity;

                $(".options").each(function(){
                    let option = $(this).attr("option-name");
                    data.options[option] = $(this).val();
                });
            }

            data.options = JSON.stringify(data.options);
            console.log(data.options);

            // Send to xcart 
            $.ajax({ url: "/cart", method: "POST", data,
                success: function(resp){
                    console.log(resp);
                    if(resp !== null && JSON.parse(resp))
                    {
                        resp = JSON.parse(resp);
                        if(resp.success){

                            $(".items").html(resp.items);
                            $(".subtotal").html("&#8358;" + resp.subtotal);

                            var new_item = '<div class="product-mini-cart table">';
                            new_item += '<div class="product-thumb">';
                            new_item += '<a href="/category/'+ resp.product.slug + '/' + resp.product.id + '" class="product-thumb-link"><img alt="" src="'+ resp.product.image +'"></a>';
                            new_item += '</div>'
                            new_item += '<div class="product-info">';
                            new_item += '<h3 class="product-title"><a href="#">'+ resp.product.name +'</a></h3>';
                            new_item += '<div class="product-price">';
                            new_item += '<ins><span>&#8358;'+ resp.product.price +'</span></ins>';
                            new_item += '<del><span>&#8358;'+ resp.product.price +'</span></del>';
                            new_item += '</div>';
                            new_item += '<div class="product-rate">';
                            new_item += '<div class="product-rating" style="width:100%"></div>';
                            new_item += '</div>';
                            new_item += '</div>';
                            new_item += '</div>';

                            $("#items-list").append(new_item);

                            $("#r-title").html("Added To Cart").addClass("text-success");
                            $("#r-message").html(resp.message);
                            $("#cresponse").fadeIn();
                        }
                        else if(resp.error) {
                            $("#r-title").html("Error Occured!").addClass("text-danger");
                            $("#r-message").html(res.error.message);
                            $("#cresponse").fadeIn();
                        }
                    }
                }
            })
        });
    };

    cartManager();

    var wishlist = () => {
        $(".addtowish").click(function(){
            var product = $(this).attr("pd");

            $.ajax({
                url: "/add-to-wish",
                method: "POST",
                data: {product},
                success: function(resp) {
                    if(resp == "not-auth") {
                        $("#w-title").html("Error Occured!").addClass("text-danger");
                        $("#w-message").html("You are not logged in. Login to add product to your wishlist.");
                        $("#wresponse").fadeIn();
                    }
                    else if(resp == "saved") {
                        $("#w-title").html("Added To Wish!").addClass("text-success");
                        $("#w-message").html("Product has been succesfully added to your wishlist.");
                        $("#wresponse").fadeIn();
                    }

                }
            });
        });
    };

    wishlist();

    $(".ct-qt").click(function(){
        var type = $(this).attr("data-type");
        var index = $(this).attr("it");
        var quantity = $("#quantity" + index);
        var qt = Number(quantity.val());

        if(type == "plus") {
            quantity.val(qt + 1); qt++;
        } else {
            if(qt > 1) {
                quantity.val(qt - 1); qt--;
            }
        }

        var url = "/cart/increment-item-qt/" + index;
        var data = {quantity: qt};
        $.ajax({url, method: "POST", data, 
            success: function(resp) {
                if(resp != null && JSON.parse(resp)) {
                    resp = JSON.parse(resp);
                    if(resp != null) {
                        $("#its" + index).html("&#8358;"+resp.target_subtotal);
                        $(".subtotal").html("&#8358;"+resp.subtotal);
                        // $("#discount").html(resp.discount);
                        $("#acc_total").html("&#8358;"+resp.total);
                    }
                }
            }
        });

    });



    $(".quick").click(function(){
        $("#quickview").fadeIn();

        var product = $(this).attr("tg");
        $.ajax({
            url: "/get_product_info",
            method: "POST",
            data: {product},
            success: function(resp) {

                if(resp != null && JSON.parse(resp)) {
                    resp = JSON.parse(resp);

                    $("#q-add").attr("pd", resp.product_id);

                    var images = resp.images.split(",");
                    $("#q-img").height("550").attr("src", "/resources/product_images" + images[0]);

                    $("#q-title").html(resp.name);

                    if(resp.discount > 0) {
                        $("#q-price").html("&#8358;"+resp.sale_price);
                        $("#q-discount").html('<strike id="q-discount">&#8358;' + resp.price +'</strike>');
                    } else {
                        $("#q-price").html("&#8358;"+resp.price);
                    }

                    if(resp.outOfStock == 0) {
                        $("#q-out").hide();
                    } else {
                        $("#q-add").hide();
                    }



                    $("#q-desc").html(resp.desc.substring(0, 220) + "...");


                    let variation_v = "";
                    for (let i = 0; i < resp.variation.length; i++) {
                        let option_name = resp.variation[i].name.toLowerCase();
                        variation_v += '<div class="col-md-6">';
                        variation_v += '<div class="' + option_name + '-wrap">';
                        variation_v += '<p class="' + option_name + '-desc">';
                        variation_v += "<b style='text-transform:uppercase;'>"+resp.variation[i].name+"</b>";  
                        variation_v += '<span>';
                        variation_v += '<select name="' + option_name + '" class="form-control options border" option-name="' + option_name + '">';
                        
                        resp.variation[i].options.forEach(option => {
                            variation_v += '<option value="'+ option +'">'+ option +' </option>';
                        });
                        
                        variation_v += '</select>';                                                                                                                                          
                        variation_v += '</span>';                                                                                                                                          
                        variation_v += '</p>';
                        variation_v += '</div>';
                        variation_v += '</div>';
                    }

                    $("#variations").html(variation_v);

                    $("#q-loader").hide();
                    $("#qv-data-holder").show();
                    
                }
            }
        });
    });

    $("#state, #receiver_state").change(function(){
        let state = $(this).val();
        let country = $("#country").val() || "Nigeria";

        if(country == "Nigeria") { 
            $("#shipping-price").css("color","#595959");

            $.ajax({
                url:"/shipping_prices", method: "GET", data: {state, country}, 
                success: function(resp) {
                    if(resp != null && JSON.parse(resp)) {
                        resp = JSON.parse(resp);
                        $("#shipping-price").html("&#8358;" + resp.shipping);
                        $("#acc_total").html("&#8358;" + resp.total);
                    }
                }
            });

        } 
        else {
            $("#shipping-price").css("color","red").html("<small>(no shipping option available)</small>");
        }
    });

    $(".view-mode").click(function(){
        var mode = $(this).attr("mode");
        $.ajax({url: "/view-mode", method: "GET", data: {mode}, success: function(resp){
            if(resp == "set") {
                location.reload();
            }
        }});
    });

    $(".rm-db").click(function(){
        var index = $(this).attr("file-id");
        $.ajax({
            url: "/remove-picture/"+product_id,
            method: "POST",
            data: {index},
            success: function(resp) {
                if(resp == "removed") {
                    $("#"+index+"-pre").remove();
                }
            }
        });
    });

})();