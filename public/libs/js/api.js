(function () {

    var step = new Step();
    step.init();

    var selected = [];

    var options = {
        display: "#GalleryBox",
        thumbnail: {
            node: "div",
            style: "thumbnail small"
        },
        removeBtn: { node: "span", style: "flaticon2-rubbish-bin text-danger", text: ""},
        clearInitial: false,
    }

    var imageArr = [];

    var target_id = null;

    $("#Gallery").change(function () {

        imageArr = ImageViewer("#Gallery", options).images;
        if (imageArr.length != 0) {
            $("#GalleryBox").removeClass("hide").show();
        }
    });

    function varImageAction() {

        $(".varImages").change(function(){

            var display = "#"+$(this).attr("display-id");
            var IdName = "#"+$(this).attr("id");
            var Doptions = 
            {
                display,
                thumbnail: { node: "div", style: "var-im-item-wrapper d-block"},
                removeBtn: { node: "span", style: "flaticon2-rubbish-bin var-rm-item text-danger", text: ""},
                showRemoveBtn: true,
                clearInitial: false,
            }

            $(display).children(".img-act").remove();
            $(this).parent("label").removeClass("hide");

            ImageViewer(IdName, Doptions);
            $(".var-rm-item").click(function(e){ e.preventDefault() });
        });


    }

    varImageAction();

    $("#Image").change(function () {
        options2 = {
            display: "#mainImage",
            thumbnail: {
                node: "div",
                style: ""
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

    $(".ajaxForm").submit(function (event) {
        event.preventDefault();
        var form = $(this);
        // Form data Validation
        var validation = true;

        $(this).children().find('[validate-field="true"]').each(function () {
            $(this).css("border-color", "#dcdcdc").siblings('[validation-result="true"]').remove();

            if ($(this).val() == "") {
                var message = $(this).attr("validation-message");
                $(this).css("border-color", "red").after('<span class="text-danger" validation-result="true">' + message + '</span>');
                validation = false;
            }
        });


        if (validation) {

            let edit = true;
            if (!form.attr("edit") && form.attr("enctype") == "multipart/form-data") {
                edit = false;

                // if (imageArr.length == 0) {
                //     step.modal("error", "Please add Product Images");
                //     return;
                // }
            }

            let api_modal = false, api_modal_mode = "";
            if(form.attr("api-modal-form")) { api_modal = true; api_modal_mode = form.attr("api-modal-form"); }

            let reset = false, resetWidget = null;
            if(form.attr("form-reset")) 
            {
                reset = true;
                resetWidget = form.attr("form-reset");
            }

            var data = new FormData(this),
                url = form.attr("action"),
                method = form.attr("method");

            if (form.attr("add-product") == "true") 
            {
                step.loader(true, "Saving Product...");
            }
            else if(edit) 
            {
                step.loader(true, "Saving changes...");
            }

            if (imageArr.length > 0) { imageArr.forEach(image => { data.append("gallery[]", image); }); }

            $.ajax({
                url, method, data, cache: false, contentType: false, processData: false,
                beforeSend: function () {

                },
                success: function (res) {
                    console.log(res);

                    if (res !== null && JSON.parse(res)) 
                    {
                        
                        res = JSON.parse(res);
                        step.loader(false);

                        if(api_modal) 
                        { 
                            $('.kt_datatable').KTDatatable('reload');
                            $('[data-dismiss="modal"]').trigger("click");
                        }

                        if (res.success) 
                        {

                            if (!edit) 
                            {
                                imageArr = [];
                                $("input, textarea").val("");
                            }

                            if(!edit && form.attr("add-product") == "true") 
                            {
                                $("#mainImage").html("");
                                $("#GalleryBox").html("").hide();
                                $("[api-variation-list]").html("");
                            }

                            step.modal("success", res.message);

                            if(reset) 
                            {
                                $('[default="true"').children().find("input").val("");
                                $(resetWidget).children('[cascade="true"]').remove();
                            }

                        }
                        else if (res.error) 
                        {
                            step.modal("error", res.error.message);
                        }

                        return;
                    }
                }
            });

        } else 
        {
            step.modal("error", "Some required fields are empty. Please check and fill all required fields before submitting the form.");
        }
    });

    $('[api-options]').each(function () {
        let select = $(this); url = $(this).attr("api-options");
        
        let selected = null;
        if($(this).attr("api-selected")) {
            selected = $(this).attr("api-selected");
        }

        $.ajax({
            url, method: "GET", data: {}, success: function (list) {
                //console.log(list);
                if (list != null && JSON.parse(list)) {
                    list = JSON.parse(list);
                    list.data.forEach(option => {

                        let d = ""; 
                        
                        if(option.id == selected) { d = "selected"; select.removeAttr("disabled"); }

                        let item = "<option value='" + option.id + "' " + d + " >" + option.name + "</option>";
                        select.append(item);

                    });

                    // $('select').selectpicker();
                }
                else {
                    console.error(list);
                }
            }
        });
    });

 
    $('[api-change]').change(function () {
        let url = $(this).attr("api-change") + "/" + $(this).val();
        let object = $(this).attr("api-result-target"), select = $(object);

        //console.log(url);

        $.ajax({
            url, method: "GET", data: {}, success: function (list) {
                //console.log(list);
                if (list != null && JSON.parse(list)) {
                    select.html("").removeAttr("disabled");
                    list = JSON.parse(list);

                    if(select.attr("api-change")) {
                        target = select.attr("api-result-target");
                        $(target).html("").attr("disabled", "disabled");
                    }

                    if (list.data.length == 0) {
                        select.attr("disabled", "disabled");
                        return;
                    }
                    let first_item = '<option value="">**select an option**</option>';
                    select.append(first_item);


                    list.data.forEach(option => {
                        let item = "<option value='" + option.id + "'>" + option.name + "</option>";
                        select.append(item);

                        // $('select').selectpicker();
                    });


                }
                else {
                    console.error(list);
                }
            }
        })
    });

    $('[api-catalogue]').each(function () {

        let box = $(this); column_size = $(this).attr("column-size"); url = $(this).attr("api-catalogue");

        $.ajax({
            url, method: "GET", data: {}, success: function (data) {
                //console.log(data);
                if (data != null && JSON.parse(data)) {
                    data = JSON.parse(data);
                    data.forEach(item => {
                        let card = '<div class=" p-1 '+ column_size +'">';
                        card += '<div class=" p-2 bg-white border-radius">';

                        card += '<div class="product-img-outer">';
                        card += '<a href="/a/products/view/'+ item.id +'"><img class="product_image" src="'+ "/src/images/"+ item.image +'" alt="'+ item.name +'"></a>';
                        card += '</div>';

                        card += '<p class="product-title"><a href="/a/products/view/'+ item.id +'">'+ item.name +'</a></p>';
                        
                        // card += '<div class="d-flex justify-content-between">';
                        card += '<p class="product-price">'+ item.price +'</p>';
                        card += '<p class="product-description">'+ item.collection + '.</p>';

                        card += '</div>';
                        card += '</div>';

                        box.append(card);
                    });
                }
                else {
                    console.error(data);
                }
            }
        });
    });

    $('[api-image-remover]').click(function(){

        var image_id = $(this).attr("api-image-id"), image_location = $(this).attr("api-image-url");
        let target = $(this).parent('[api-image="'+ image_id +'"]');
        let data = {image_id, image_location}, url = $(this).attr("api-image-remover");

        $.ajax({
            url, method: "POST", data, success: function(response) {
                //console.log(response);

                if (response !== null && JSON.parse(response)) {
                    response = JSON.parse(response);

                    if (response.success) {
                        target.remove();
                        step.modal("success", response.message);
                    }
                    else if (response.error) {
                        step.modal("error", response.error.message);
                    }

                    return;
                }
            }
        });

    });

    $('[api-variation-remover]').click(function(){

        let object = $(this).attr("object-item"); object = $(object);
        let url = $(this).attr("api-variation-remover");

        $.ajax({
            url, method: "POST", data: null, success: function(response) {
                console.log(response);
                if (response !== null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if (response.success) 
                    {
                        object.remove();
                        step.modal("success", response.message);

                        if(response.content != undefined) 
                        {
                            var variationField = "#variation"+response.variation;
                            var variationGroup = "#variationGroup"+response.variation;

                            $(variationField).val(response.content);

                            response.group = "#var-group-"+response.group;
                            console.log(response.group);
                            $(response.group).remove(); 
                        }

                        if(object.attr("api-refresh-variation") == "true"){
                            variation_creator();
                        }

                        if(response.clear != undefined) {
                            $(".variation-wrapper").remove();
                        }

                        // $("[api-variation-creator]").trigger("blur");
                    }
                    else if (response.error) {
                        step.modal("error", response.error.message);
                    }

                    return;
                }
            }
        })


    });

    $('[api-remove-var-image]').click(function(){
        
        let object = $(this).parent("[image-viewer_file]");
        let index = $(this).attr("api-remove-var-image");
        let group = $(this).attr("gid");
        url = "/api/a/products/variation-image-remover/"+$(this).attr("pid");

        $.ajax({
            url, method: "POST", data: {index, group}, success: function(res) {
                //console.log(res);

                if (res !== null && JSON.parse(res)) {
                    res = JSON.parse(res);

                    if (res.success) 
                    {
                        object.remove();
                        step.modal("success", res.message);
                    }
                    else if (res.error) {

                        step.modal("error", res.error.message);
                    }
                }
            }
        });
    });

    if($("#addSkeletonBtn2"))
    {
        let skeleton = "";
        setTimeout(function(){ skeleton = $("#skeleton").html()}, 2000);
        
        $("#addSkeletonBtn2").click(function(){
            $("#skeletonForm").append('<div class="col-md-12 mt-1">'+skeleton+"</div>");
            variation_selection();
            // variation_creator();
            $(".var_name").on("keyup", function(){
                $("#CreateVariationOptions").removeClass("hide").fadeIn();
            });
            rni++;
        });

    }

    $("#CreateVariationOptions").click(function(e){
        e.preventDefault();
    
        variation_creator();
    
        var step = $(this).attr("href");
        var offset = $(step).offset();
        window.scrollTo({
            top: offset.top,
            left: 0,
            behavior: 'smooth'
        });
    });

    function variation_creator()
    {
        // $("[api-variation-creator]").blur(function(){
    
            index = 0;
            variations = [];
            longest = $("[api-variation-creator]").last().val().split(",").length;
    
            $("[api-variation-creator]").each(function(){
    
                group = $(this).parent("div").parent("div").siblings(".var_type").children().find("select.variations");
                value = $(this).val();
    
                if(value != "") {
    
                    if(group.val() == "") {
    
                        step.modal("error", "variation type is required and must be before adding options.");
                        return;
                    }
                    else
                    {
                        if(value.indexOf(",") > -1) { value = value.replace(" ", "").split(","); 
                            // longest = (value.length > longest) ? value.length : longest; 
                        } 
                        else { 
                            // value = Array(value.replace(" ","")); 
                            // longest = (value.length > longest) ? value.length : longest; 
                        }
    
                        if(index == 0) {
    
                            value.forEach(name => { 
    
                                variation = JSON.stringify({name});
    
                                if(variations.indexOf(variation) > -1) {
    
                                    step.modal("error", "Duplicate variant <b>"+ JSON.parse(variation).name +"</b> cannot be created.");
                                    return;
                                }
                                else {
                                    variations.push(variation);
                                }
                            });
                        }
                        else 
                        {
                            variation_holder = []
                            counter = 0;
                            for (let i = 0; i < variations.length; i++) {
                                
                                for (let j = 0; j < value.length; j++) {
    
                                    var_index = JSON.parse(variations[i]);
                                    variation = JSON.stringify({name: var_index.name + "/" + value[j]});
    
                                    if(variation_holder.indexOf(variation) > -1) {
    
                                        step.modal("error", "Duplicate variant <b>"+ var_index.name +"</b> cannot be created.");
                                        break;
    
                                    }
                                    else {
                                        variation_holder[counter] = variation;
                                    }
    
                                    counter++;
                                }
                            }
    
                            variations = variation_holder;
                        }
        
                        index++;
    
                    }
                }
            });
            
            //console.log(variations); return;

            create_variations_combined_list(variations, longest);
    
        // });

    }

    function create_variations_combined_list(variations, longest) {
        //console.log(variations);

        variations = JSON.stringify(variations);

        price = $("#Price").val(); discount = $("#Discount").val();
        if(Number(discount) > 0) { price = $("#DiscountPrice").val(); }
        
        $.ajax({
            url: "/a/product/create_variations_list_options", method: "POST", data: {variations, longest, price},
            success: function(response) {
                //console.log(response);
                $("[api-variation-list]").removeClass("hide").html(response);
                varImageAction();
            }
        });

    }

    // variation_creator();

    function variation_selection() {

        $(".variations").change(function(){
            let val = $(this).val();
            if(selected.indexOf(val) < 0) 
            {
                selected.push(val);
            }
            else 
            {
                step.modal("error", "This option has been selected. kindly select another variant");
                $(this).children("option").first().attr("selected","selected");
            }
        });
    }

    variation_selection();

    $('[api-order-call]').click(function(){
        var id = $(this).attr("api-order-call");
        var mode = $(this).attr("api-mode");
        url = "/api/a/" + mode + "-order";
        $.ajax({
            url, method: "POST", data: {id}, success: function(response) {
                //console.log(response);
                if(response != null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if(response.success) 
                    {
                        var message = '<div class="toast-notification success">\
                            <h6 class="text-white"><b>Successfull!</b></h6><p>'+ response.message + '</p>\
                            <span class="tos-close flaticon-circle text-white"><span>\
                        </div>';
                        $("body").append(message);
                   
                    } else if(response.error) 
                    {
                        var message = '<div class="toast-notification error">\
                            <h6 class="text-white"><b>Error Occured!</b></h6><p>'+ response.error.message + '</p>\
                            <span class="tos-close flaticon-circle text-white"><span>\
                        </div>';
                        $("body").append(message);
                    }

                    $(".tos-close").click(function(){ $(".toast-notification").remove(); });
                    $(".toast-notification").fadeOut(6000);
                }
            }
        });
    });

    $('[api-approve-product]').click(function(){
        target = $(this);
        product = $(this).attr("api-approve-product");
        $.ajax({
            url: "/api/a/approve-product", method: "POST", data: {product}, success: function(response) {
                //console.log(response);
                if(response != null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if(response.success) {
                        step.modal("success", response.message);
                    }
                    else if(response.error) {
                        step.modal("error", response.error.message );

                    }
                }
            }
        });

    }); 

    $('[api-disapprove-product]').click(function(){
        target = $(this);
        product = $(this).attr("api-disapprove-product");
        $("#DisApprovedFieldID").val(product);
        // $.ajax({
        //     url: "/api/a/disapprove-product", method: "POST", data: {product}, success: function(response) {
        //         //console.log(response);
        //         if(response != null && JSON.parse(response)) {
        //             response = JSON.parse(response);
        //             if(response.success) {
        //                 step.modal("success", response.message);
        //                 target.hide();
        //                 target.siblings(".approve").show();
        //             }
        //             else if(response.error) {
        //                 step.modal("error", response.error.message );

        //             }
        //         }
        //     }
        // });

    }); 

    $('[api-hide-product]').click(function(){

        target = $(this);
        product = $(this).attr("api-hide-product");
        $.ajax({
            url: "/api/a/hide-product", method: "POST", data: {product}, success: function(response) {
                //console.log(response);
                if(response != null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if(response.success) {
                        step.modal("success", response.message);
                        target.hide();
                        target.siblings(".show-btn").fadeIn();
                    }
                    else if(response.error) {
                        step.modal("error", response.error.message );
                    }
                }
            }
        });

    });

    $('[api-show-product]').click(function(){
        
        target = $(this);
        product = $(this).attr("api-show-product");
        $.ajax({
            url: "/api/a/show-product", method: "POST", data: {product}, success: function(response) {
                //console.log(response);
                if(response != null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if(response.success) {
                        step.modal("success", response.message);
                        target.hide();
                        target.siblings(".hide-btn").show();
                    }
                    else if(response.error) {
                        step.modal("error", response.error.message );
                    }
                }
            }
        });

    });

    $('[api-item-call]').click(function()
    {
        var id = $(this).attr("api-item-call"), mode = $(this).attr("api-mode"), url = "/api/a/" + mode + "-order-item";

        $.ajax({
            url, method: "POST", data: {id}, success: function(response) {
                //console.log(response);
                if(response != null && JSON.parse(response)) {
                    response = JSON.parse(response);
                    if(response.success) 
                    {
                        message = '<div class="toast-notification success">\
                            <span class="tos-close flaticon-circle text-white"><span>\
                            <h6 class="text-white"><b>Successfull!</b></h6><p>'+ response.message + '</p>\
                        </div>';
                        $("body").append(message);
                   
                    } else if(response.error) 
                    {
                        message = '<div class="toast-notification error">\
                            <span class="tos-close flaticon-circle text-white"><span>\
                            <h6 class="text-white"><b>Error Occured!</b></h6><p>'+ response.error.message + '</p>\
                        </div>';
                        $("body").append(message);
                    }

                    $(".tos-close").click(function(){ $(".toast-notification").remove(); });
                    $(".toast-notification").fadeOut(6000);
                }
            }
        });
    });


    $("#ProductType").change(function () {
        if ($(this).val() == '1') 
        {
            $("#Color").removeAttr("disabled")
            $(".PColor").removeClass("hide").fadeIn();

            // Badges
            // $("#ImageBadge").attr("step-validate", "3").attr("step-badge","3").removeClass("hide").show();
            // $("#VariationBadge").removeAttr("step-validate", "3").removeAttr("step-badge","3").addClass("hide").hide();

            // // Forms 
            // $("#ImageStep").attr("step-id", "3");
            // $("#VariationStep").removeAttr("step-id").addClass("hide");

            $("#Quantity").attr("step-field-required","true").attr("validate-field", "true").removeAttr("disabled");
            $(".PQuantity").removeClass("hide").fadeIn();
        }
        else 
        {
            $("#Color").attr("disabled", "disabled")
            $(".PColor").addClass("hide").fadeOut();

            // Badges
            // $("#VariationBadge").attr("step-validate", "3").attr("step-badge","3").removeClass("hide").show();
            // $("#ImageBadge").removeAttr("step-validate", "3").removeAttr("step-badge","3").addClass("hide").hide();

            // // Forms 
            // $("#VariationStep").attr("step-id", "3");
            // $("#ImageStep").removeAttr("step-id").addClass("hide");

            $("#Quantity").removeAttr("step-field-required").removeAttr("validate-field").attr("disabled", "disabled");
            $(".PQuantity").addClass("hide").fadeOut();
        }
    });

    $(".cll").click(function () {

        $(".col-section").removeClass("active");
        $(".ctt").prop("checked", false);
        $(".sctt").prop("checked", false);

        $(this).prop("checked", true);
        if ($(this).prop("checked") == true) {
            $(this).parent("label").parent(".col-section").addClass("active");
        }
    });

    $(".ctt").click(function () {

        $(".cat-section").removeClass("active");
        $(".sctt").prop("checked", false);

        $(this).prop("checked", true);
        if ($(this).prop("checked") == true) {
            $(this).parent("label").parent(".cat-section").addClass("active");
        }
    });

    function Calculate(DicountField) 
    {
        var discount = Number($(DicountField).val());
        var price = Number($("#Price").val().replace(",", ""));

        if(discount < 80) 
        {
            $("#DiGroup").siblings("span").html("");

            var discountAmount = (discount/100) * price;
            var discountPrice = price - discountAmount;
    
            $("#DiscountPrice").val(discountPrice.toFixed(2));
        }
        else 
        {
            $("#DiGroup").siblings("span").html("maximum of 80% discount is allowed");
        }

    }

    $("#Discount").on("change", function(){ Calculate(this); });
    $("#Discount").on("keyup", function(){ Calculate(this); });
    $("#Discount").on("blur", function(){ Calculate(this); });

    $(".tos-close").click(function(){ $(".toast-notification").remove(); });
    $(".toast-notification").fadeOut(6000);

    // setTimeout(function(){
    //     $('select').selectpicker();
    // }, 3000);
    
})();