(function(){

    $('[data-tr-o="true"]').submit(function (event) {
        event.preventDefault();
        
        var form = $(this);

        var validation = true;

        $(this).children().find('[validate-field="true"]').each(function () {
            $(this).css("border-color", "#dcdcdc").siblings('[validation-result="true"]').remove();

            if ($(this).val() == "") {
                var message = $(this).attr("validation-message");
                $(this).css("border-color", "red").after('<small class="text-danger" validation-result="true">' + message + '</small>');
                validation = false;
            }
        });


        if (validation) {

            let edit = true;
            if (!form.attr("edit")) { edit = false; }

            var data = new FormData(this),
                url = form.attr("action"),
                method = form.attr("method");

            $.ajax({
                url, method, data, cache: false, contentType: false, processData: false,
                beforeSend: function () {
                    $(".formloader").fadeIn();
                },
                success: function (response) {
                    // console.log(response);
                    if (response !== null && JSON.parse(response)) {
                        response = JSON.parse(response);
                        if (response.success) {
                            if (!edit) { $("input, textarea").val("") }
                            pop_message(true, response.message);
                            $(".rating-overlay, .form-modal").removeClass("show animated fadeIn");

                            if(response.state == "enabled") {
                                $("#WalletStatusTx").html("<small>Active<small>");
                            }
                        }
                        else if (response.error) {
                            pop_message(false, response.error.message);
                        }

                        $(".formloader").fadeOut();
                        return;
                    }
                }
            });

        } else {
            pop_message(false, "Some required fields are empty. Please check and fill all required fields before submitting the form."); 
        }
    });

    function dt_im_action(){
        $("[data-im-tg]").click(function(){
            src = $(this).attr("src");
            $("[data-im-main]").attr("src", src);
        }); 
    }
    
    var carting = () => {

        $(".cart-btn").load("/pd/cart-widget");

        let color = "", size = "", material = "";
        
        $('[data-qt-nm]').click(function(){ console.log("Clicked");

            $(".msgCenter").html("");

            aim = $(this).attr("data-qt-nm");

            tg_qty_key = $(this).attr("for");
            tg_qty = $('[data-qt-tg="'+ tg_qty_key +'"]');
            qty = tg_qty.val();

            if(Number(qty)) { qty = Number(qty) }
            else { $(".msgCenter").html("<small class='text-danger'>Invalid quantity entered.</small>"); return; }
    
            if(aim == "true") { 

                qty++; 
                
                if(tg_qty.attr("max")) {

                    max = tg_qty.attr("max");
                    if(qty > Number(max)){ return; }
                    tg_qty.val(qty);
                    
                } 
                else {
                    // Check Availability of product
                    tg_qty.val(qty);
                }
                
            }
    
            else if(aim == "false") {
                qty = (qty - 1); if(qty > 0) { tg_qty.val(qty); }
            }

            if($(this).attr("item-update")) {

                index = $(this).attr("item-update");
                $.ajax({
                    url: "/pd/update-item-quantity", method: "POST", data: {index, qty},
                    success: function(response) {
                        console.log(response);
                    }
                })

            }

        });

        $('[data-vr-opt]').change(function(){
    
            $("[data-vr-opt]").each(function(){
                if($(this).prop("checked") == true) {
    
                    if($(this).attr('data-vr-opt') == "size") { size = $(this).val(); $(".active-size").html(size); }
                    else if($(this).attr('data-vr-opt') == "color") { color = $(this).val(); }
                    else if($(this).attr('data-vr-opt') == "material") { material = $(this).val(); $(".active-material").html(material); }
                }
            });
            
            if($(this).attr("data-vr-im") == "true") {
                var _overview = false;
                if($(this).attr("carting") == "true") {
                    _overview = true;
                }

                url = "/pd/var/images/" + product, method = "POST", data = {color:color};
                $.ajax({ url, method, data, success:function(response) {
                        // console.log(response);
                        if(response != "") {
                            im = $('[data-mg-fr="'+color+'"]').attr("src");
                            $(".active-color").html(color);
                            $("[data-im-main").attr("src",im);
                            $(".slides-gallery").html(response);

                            if(_overview) {
                                cartingSlider();

                            } else {
                                initImgSlides(); 

                            }
                            dt_im_action();

                        }
                    }
                });
            }
    
            // Checking Variation Informations
            if($('[data-vr-opt="size"]').length > 0 && size == "") {} 
            else if($('[data-vr-opt="material"]').length > 0 && material == "") {}
            else { checkVariationInfo(color, size, material, product); }
    
        });

        $('[data-add-cart]').click(function(){

            $(".msgCenter").html("");
            if(($('[data-vr-opt="size"]').length > 0) && size == "") {
                $(".msgCenter").html("<small class='text-danger'>Kindly choose a size to add to cart.</small>");
                return;
            } 
            if(($('[data-vr-opt="material"]').length > 0) && material == "") {
                $(".msgCenter").html("<small class='text-danger'>Kindly choose a meterial type to add to cart.</small>");
                return;
            }

            var enabled = true;
            if($(this).attr("enable-response")) {
                enabled = false;
            }

            product = $(this).attr('data-add-cart'); quantity = $(".qty").val();

            if(!Number(quantity) || Number(quantity) < 0 || Number(quantity) == 0) {
                $(".msgCenter").html("<small class='text-danger'>Invalid quantity entered.</small>"); 
                return;
            }

            url = "/pd/add-to-cart", method = "POST", data = {product, quantity, color, size};
            $.ajax({
                url, method, data, success:function(response) {
                    console.log(response);
                    if(response != "" && JSON.parse(response)) {
                        
                        response = JSON.parse(response);
                        if(response.success) {

                            if(!enabled) {
                                $(".carting-overlay").toggleClass("show fadeIn animated");
                                $(".carting-content-box").toggleClass("fadeInRight animated");
                            }
                            
                            url = "/pd/i/carting-response/"+response.item;
                            $(".cart-btn").load("/pd/cart-widget");
                            $(".carting-body").load(url); 
                            removeCartItem();
                        }

                        else if(response.error) {
                            $(".msgCenter").html('<p class="text-danger"><b>' + response.error.message + '</b></p>');
                        }

                    }
                }
            })
        });

        cartingSlider();

        $(".carting-overlay, .carting-close").click(function(event){
            if(event.target == $(this)[0]) {
                $(".carting-overlay").removeClass("show fadeIn animated");
                $(".carting-content-box").removeClass("fadeInRight animated");
            }
        });
    }

    $(".rmCartItem").click(function(event){
        event.preventDefault();
        id = $(this).attr("item");
        $('[remove-id]').val(id);
        $("#removeForm").submit();
    });

    $("[data-prepare-cart]").click(function(){

        product = $(this).attr("data-prepare-cart");
        url = "/pd/carting-data", method = "POST";
        $.ajax({
            url, method, data:{product}, 
            beforeSend: function() {
                $(".carting-body").html('<div class="carting-loader justify-content-center d-flex"><div class="spinner-grow bg-primary"></div></div>');
                $(".carting-overlay").toggleClass("show fadeIn animated");
                $(".carting-content-box").toggleClass("fadeInRight animated");
            },
            success: function(response) {
                if(response != null) {
                    $(".carting-body").html(response);
                    carting();
                }
            }
        });
    });

    $("[data-prepare-wish]").click(function(){

        target = $(this);
        initial = target.html();

        product = $(this).attr("data-prepare-wish");
        url = "/uac/save-item-to-wish", method = "POST";
        $.ajax({
            url, method, data:{product}, 
            beforeSend: function() {
                target.html('<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="sr-only text-white"></span></div>')
            },
            success: function(response) {
                console.log(response);
                if(response != null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if(response.success) {
                        target.html("<i class='flaticon2-check-mark text-success'></i>");
                    }
                    else if(response.exists) {
                        
                        target.html(initial);
                    }
                    else if(response.auth_needed) {
                        $(".authm-overlay").toggleClass("show animated fadeIn");
                        target.html(initial);
                    }
                }
            }
        });
    });

    $("[data-prepare-rating]").click(function(event){
        event.preventDefault();
        product = $(this).attr("data-prepare-rating");
        $("#RateProduct").val(product);

        $(".rating-overlay").toggleClass("show fadeIn animated");
    });

    $(".modal-cancel").click(function(){
        $(".authm-overlay, .rating-overlay, .password-modal, .form-modal").removeClass("show animated fadeIn");
    });
    
    $(".authm-overlay, .rating-overlay, .password-modal, .form-modal").click(function(event){
        if(event.target == $(this)[0]) {
            $(this).removeClass("show animated fadeIn");
        }
    });

    $(".rate-close").click(function(){
        $(".rating-overlay").removeClass("show fadeIn animated");
    }); 

    function checkVariationInfo(color, size, material, product) 
    {
        url = "/pd/get-var-information/ss/"+product; method = "POST";
        $.ajax({
            url, method, data: {color, size, material}, success: function(response) {
                console.log(response);

                if (response !== null && JSON.parse(response)) { 
                    response = JSON.parse(response);

                    if(response.variant !== "")
                    {
                        $('[data-pd-price="true"]').html('<h5 class="mr-1"><b>'+ response.price +'</b></h5>');
                        $('.qty').attr("max", response.quantity).val("1");
                        
                        if(Number(response.quantity) > 5) { 
                            $(".availabily-message-box").html("<p class='text-primary'><small><b>" + response.quantity + " left in stock.</b></small></p>");
                        }
                        else 
                        {
                            $(".availabily-message-box").html("<p class='text-danger'><small><b> only " + response.quantity + " left in stock.</b></small></p>");
                        }
                    }
                    else if(response.variant == "")
                    {
                        if(response.in_stock == false) {
                            $(".availabily-message-box").html("<p class='text-danger'><small><b> Not available in stock.</b></small></p>");
                        }
                    }

                }
            }
        });
    }

    function initImgSlides() {
        var imgSlides = $('.images-slides');
        imgSlides.owlCarousel({
            loop: false,
            dots: false,
            nav: true,
            autoplay: false,
            navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                960: {
                    items: 3
                },
                1200: {
                    items: 4
                },
                1920: {
                    items: 6
                }
            }
        }); 
    }

    function cartingSlider() {
        // carting-slideshow
        var carting = $('.carting-slideshow, .images-slides');
        carting.owlCarousel({
            loop: true,
            dots: true,
            nav: true,
            autoplay: true,
            navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
            autoplayTimeout:6000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 1
                },
                960: {
                    items: 1
                },
                1200: {
                    items: 1
                },
                1920: {
                    items: 1
                }
            }
        }); 
    }

    function removeCartItem() {
        
        setTimeout(function(){
            $(".ctRemoveItem").click(function(event){
                event.preventDefault();
                
                item = $(this).attr("dtr-item"); 
                url = "/pd/cart/remove"; 
                method = "POST";
    
                $.ajax({ url, method, data: {item}, success:function(response) {
                        
                        console.log(response);
                        if(response != "" && JSON.parse(response)) {
                            response = JSON.parse(response);
                            if(response.success) {
                                $(".carting-body").load("/pd/cart-removed");
                                $(".cart-btn").load("/pd/cart-widget");
                            }
                        }
                    }
                });
            });
        }, 2000)


    }

    dt_im_action();
    carting();

    var brands = Array(); colors = Array(); sizes = Array();

    function colate_filters() {

        $(".form-check-input").each(function(){

            if($(this).prop("checked") == true) {
                if($(this).attr("name") == "color") {
                    colors.push($(this).val());
                } 
                else if($(this).attr("name") == "size") {
                    sizes.push($(this).val());
                }
                else if($(this).attr("name") == "brand") {
                    brands.push($(this).val());
                }
            }

        });

    }

    $(".form-check-input").change(function(){

        colate_filters()

        url = window.location.href;
        _priceParam = "";
        
        
        if((url.indexOf("?") > -1)) {

            url_split = url.split("?");
            url = url_split[0];

            if(url_split[1].indexOf("price_range=") > -1) {
                getPParam = url_split[1].split("price_range=");
                getPParam = getPParam[getPParam.length - 1];
                _priceParam = "&price_range=" + getPParam;
            }

        }

        filters = "?filter=1";

        if(brands.length > 0) { filters += "&brands=" + brands.join(",").replace(" ", "-"); }
        if(colors.length > 0) { filters += "&colors=" + colors.join(",").replace(" ", "-"); }
        if(sizes.length > 0) { filters += "&sizes=" + sizes.join(",").replace(" ", "-"); }


        window.location.href = url + filters + _priceParam;

    });


    $("#sort").change(function(){
        url = window.location.href;
        if(url.indexOf("?") > -1) {
            if(url.indexOf("sort=") > -1){

            }
            window.location.href += "&sort=" + $(this).val();
        }
        else {
            window.location.href += "?sort=" + $(this).val();
        }
    });

    $(".form-control-range").change(function(){
        val = $(this).val(); $('[name="rg_nd"]').val(val);
    });

    $('[data-vars-img-mode]').click(function(){

        src = $(this).attr("data-vars-img-mode");
        target = $(this).attr("data-cot-label");
        
        $('[data-cot-label=' + target + ']').removeClass("active"); $(this).addClass("active");
        $('[data-img-label=' + target + ']').attr("src", src);

    });

    $(".apP_range").click(function(event){
        event.preventDefault();

        colate_filters();

        start_price = $('[name="rg_st"]').val();
        end_price = $('[name="rg_nd"]').val();

        url = window.location.href;
        if(url.indexOf("?")) { url = url.split('?')[0]; }

        filters = "?filter=1";

        if(brands.length > 0) { filters += "&brands=" + brands.join(",").replace(" ", "-"); }
        if(colors.length > 0) { filters += "&colors=" + colors.join(",").replace(" ", "-"); }
        if(sizes.length > 0) { filters += "&sizes=" + sizes.join(",").replace(" ", "-"); }

       
        _priceParam = "&price_range=" + start_price + "," + end_price;


        window.location.href = url + filters + _priceParam;

    });

    $('[auth-pop-form]').submit(function(event){
        event.preventDefault();

        var form = $(this);
        // Form data Validation
        var validation = true;

        $(this).children().find('[validate-field="true"]').each(function () {
            $(this).css("border-color", "#dcdcdc").siblings('[validation-result="true"]').remove();

            if ($(this).val() == "") {
                var message = $(this).attr("validation-message");
                $(this).css("border-color", "red").after('<small class="text-danger" validation-result="true">' + message + '</small>');
                validation = false;
            }
        });


        if (validation) {

            var data = new FormData(this),
                url = form.attr("action"),
                method = form.attr("method");

                $.ajax({
                    url, method, data, cache: false, contentType: false, processData: false,
                    beforeSend: function () {
    
                    },
                    success: function (response) {
                        console.log(response);
    
                        if (response !== null && JSON.parse(response)) {
                            response = JSON.parse(response);
                                
                            if (response.success) {
                                $('.authm-overlay').fadeOut();
                                window.location.href = window.location.href;
                            }
                            else if (response.error) {
                                $(".authm-error").html(response.error.message);
                            }
    
                            return;
                        }
                    }
                });

        }
    });

    $('.deposit-now').click(function(event){
        event.preventDefault();

        var form = $(this);
        // Form data Validation
        var validation = true;

        $(this).children().find('[validate-field="true"]').each(function () {
            $(this).css("border-color", "#dcdcdc").siblings('[validation-result="true"]').remove();

            if ($(this).val() == "") {
                var message = $(this).attr("validation-message");
                $(this).css("border-color", "red").after('<small class="text-danger" validation-result="true">' + message + '</small>');
                validation = false;
            }
        });


        if (validation) {
            $('[deposit-form]').submit();
        }
    });

    function pop_message(mode, text) {

        if(mode == true) {
            $("body").append("<div class='pop-message success'><h6 class='text-success'><b> <i class='mdi mdi-check text-success'></i> Successfull!</b></h6><div class=''><small>" + text + "</small> <span class='pop-close mdi mdi-window-close'></span></div></div>");
        }
        else {
            $("body").append("<div class='pop-message error'><h6 class='text-danger'><b> <i class='mdi mdi-information-outline text-danger'></i> Error Occured!</b></h6><div class=''><small>" + text + "</small> <span class='pop-close mdi mdi-window-close'></span></div></div>");
        }

        $(".pop-close").click(function(){
            $(".pop-message").remove();
        })
        
        setTimeout(function(){$(".pop-message").fadeOut()},6000);
    }

    $("[data-address-remover]").click(function(event){
        event.preventDefault();

        url = $(this).attr("href");
        $.ajax({
            url, method: "POST", data:{}, success: function(response) {
                console.log(response);
                if(response != null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if(response.success) {
                        pop_message(true, response.message);
                        // window.location.href = window.location.href;
                    }
                    else if(response.error) {
                        pop_message(false, response.error.message);
                    }
                }
            }
        });
    });

    $("[data-wishlist-remover]").click(function(event){
        event.preventDefault();

        url = $(this).attr("href");
        $.ajax({
            url, method: "POST", data:{}, success: function(response) {
                console.log(response);
                if(response != null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if(response.success) {
                        pop_message(true, response.message);
                        // window.location.href = window.location.href;
                    }
                    else if(response.error) {
                        pop_message(false, response.error.message);
                    }
                }
            }
        });
    });

    $(".deposit-btn").click(function(event){
        event.preventDefault();

        $(".deposit-pop").toggleClass("show fadeIn animated");
    });

    $("[action-modal]").click(function(){
        modal = $(this).attr("action-modal");
        $(modal).toggleClass("show animated fadeIn");
    });

    $("#WalletStatus").change(function(){

        mode = 0; if($(this).prop("checked") == true) { mode = 1; }
        if(mode == 1) {
            $("#EnableWallet").toggleClass("show animated fadeIn");
        }

        $.post("/uac/wallet-settings", {mode}, function(response){
            console.log(response);
            if (response !== null && JSON.parse(response)) {
                response = JSON.parse(response);
                if (response.success) {
                    if(response.state == "Disabled") {
                        $("#WalletStatusTx").html("<small>Disabled<small>");
                        pop_message(true, response.message);
                    }
                    else if(response.state == "code_sent") {
                        $("#WalletStatus").removeAttr("checked");
                        $(".state_message").html(response.message);
                    }
                    else {
                        pop_message(true, response.message);
                    }
                }
                else if (response.error) {
                    pop_message(false, response.error.message);
                }

                return;
            }
        });
        
    });

})();
