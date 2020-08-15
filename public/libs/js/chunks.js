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

                        $("#r-title").html("Added To Cart").addClass("text-success");
                        $("#r-message").html(resp.message);
                        $("#cresponse").fadeIn();

                    }
                    else if(resp.error) 
                    {
                        
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