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
        removeBtn: {
            node: "button",
            style: "btn btn-icon btn-inverse-danger btn-rounded",
            text: "x"
        }
    }

    var imageArr = [];

    var target_id = null;

    $("#Gallery").change(function () {

        imageArr = ImageViewer("#Gallery", options).images;
        if (imageArr.length != 0) {
            $("#GalleryBox").removeClass("hide").show();
        }
    });


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

                if (imageArr.length == 0) {
                    step.modal("error", "Please add Product Images");
                    return;
                }
            }

            let api_modal = false, api_modal_mode = "";
            if(form.attr("api-modal-form")) { api_modal = true; api_modal_mode = form.attr("api-modal-form"); }

            let reset = false, resetWidget = null;
            if(form.attr("form-reset")) {
                reset = true;
                resetWidget = form.attr("form-reset");
            }

            var data = new FormData(this),
                url = form.attr("action"),
                method = form.attr("method");

            if (form.attr("add-product") == "true") {
                step.loader(true, "Saving Product...");
            }
            else if(edit) {
                step.loader(true, "Saving changes...");
            }

            if (imageArr.length > 0) { imageArr.forEach(image => { data.append("gallery[]", image); }); }

            $.ajax({
                url, method, data, cache: false, contentType: false, processData: false,
                beforeSend: function () {

                },
                success: function (res) {
                    console.log(res);

                    if (res !== null && JSON.parse(res)) {
                        
                        res = JSON.parse(res);
                        step.loader(false);

                        if(api_modal) { $('[data-dismiss="modal"]').trigger("click"); }

                        if (res.success) {

                            if (!edit) {
                                imageArr = [];
                                $("input, textarea").val("");
                            }

                            if(!edit && form.attr("add-product") == "true") {
                                $("#mainImage").html("");
                                $("#GalleryBox").html("").hide();
                                $("[api-variation-list]").html("");
                            }

                            step.modal("success", res.message);

                            if(reset) {
                                $('[default="true"').children().find("input").val("");
                                $(resetWidget).children('[cascade="true"]').remove();
                            }

                            if(api_modal) {

                                if(api_modal_mode == "edit") {

                                    let row = "<tr api-row-id='"+ target_id +"'>";
                                    res.data.forEach(td => {
                                        row += "<td>" + td + "</td>";
                                    });


                                    let target = $('[api-row-id="'+ target_id +'"]');
                                    actions = target.children(".actions").html();

                                    row += "<td class='actions'>" + actions + "</td>";
                                    row += "</tr>";

                                    target.after(row).remove();

                                    activate_button_actions();

                                }
                                else if(api_modal_mode == "delete") {

                                    $('[api-row-id="'+ target_id +'"]').remove();
                                }
                               
                            }

                        }
                        else if (res.error) {

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

    $('[api-options]').each(function () {
        let select = $(this); url = $(this).attr("api-options");
        
        let selected = null;
        if($(this).attr("api-selected")) {
            selected = $(this).attr("api-selected");
        }

        $.ajax({
            url, method: "GET", data: {}, success: function (list) {
                console.log(list);
                if (list != null && JSON.parse(list)) {
                    list = JSON.parse(list);
                    list.forEach(option => {

                        let d = ""; if(option.value == selected) { d = "selected"; }

                        let item = "<option value='" + option.value + "' " + d + " >" + option.name + "</option>";
                        select.append(item);
                    });
                }
                else {
                    console.error(list);
                }
            }
        });
    });

    function button_actions(id, actions, links, modal = false, modalName = "") {

        let buttons = "";
        let i = 0;

        function modal(action, modalName) {

            target = action+modalName;
            return 'type="button" data-toggle="modal" data-target="#' + target +'"';

        }

        actions.forEach(action => {
            action = action.toLowerCase().replace(" ", "");
            if (action == "edit" || action == "unblock") {
                buttons += '<a href="' + links[i] + id + '" api-edit="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-gradient-success btn-sm">' + action + '</a>';
            }

            else if (action == "unblock") {
                buttons += '<a href="' + links[i] + id + '" api-unblock="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-primary btn-sm">' + action + '</a>';
            }
            
            else if (action == "view") {
                buttons += '<a href="' + links[i] + id + '" api-view="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-gradient-info btn-sm" >' + action + '</a>';
            }

            else if (action == "show") {
                buttons += '<a href="' + links[i] + id + '" api-show="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-info btn-sm" >' + action + '</a>';
            }
            
            else if (action == "delete") {
                buttons += '<a href="' + links[i] + id + '" api-delete="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-gradient-danger btn-sm">' + action + '</a>';
            }

            else if (action == "block") {
                buttons += '<a href="' + links[i] + id + '" api-block="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-danger btn-sm">' + action + '</a>';
            }

            else if (action == "hide") {
                buttons += '<a href="' + links[i] + id + '" api-hide="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-danger btn-sm">' + action + '</a>';
            }

            else if(action == "") {
                buttons += '<p class="text-center">-</p>';
            }
            else  {
                buttons += '<a href="' + links[i] + id + '" api-edit="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-gradient-success btn-sm">' + action + '</a>';
            }

            i++;
        });

        return buttons;
    }

    $('[api-table]').each(function () {

        let table = $(this); url = $(this).attr("api-table");
        let actions = $(this).attr("api-actions");
        let links = $(this).attr("api-action-links");

        let modal = false, modalName = "";
        if($(this).attr("api-modal") != null) {
            modal = true;
            modalName = $(this).attr("api-modal");
        }


        if (actions.indexOf(",") > -1) { actions = actions.split(","); } else { actions = Array(actions); }
        if (links.indexOf(",") > -1) { links = links.split(","); } else { links = Array(links); }

        $.ajax({
            url, method: "GET", data: {}, success: function (rows) {
                console.log(rows);
                if (rows != null && JSON.parse(rows)) {
                    
                    rows = JSON.parse(rows);
                    
                    // let index = 1;
                    rows.forEach(row => {
                        var id = row[0];

                        let item = "<tr api-row-id='"+ id +"'>";
                        row.forEach(td => { item += "<td>" + td + "</td>"; });

                        item += "<td class='actions'>" + button_actions(id, actions, links, modal, modalName) + "</td>";
                        item += "</tr>";
                        table.append(item);
                    });
                }
                else {
                    console.error(rows);
                }
            }
        });
    });

    $('[api-change]').change(function () {
        let url = $(this).attr("api-change") + "/" + $(this).val();
        let object = $(this).attr("api-result-target"), select = $(object);

        $.ajax({
            url, method: "GET", data: {}, success: function (list) {
                console.log(list);
                if (list != null && JSON.parse(list)) {
                    select.html("").removeAttr("disabled");
                    list = JSON.parse(list);

                    if(select.attr("api-change")) {
                        target = select.attr("api-result-target");
                        $(target).html("").attr("disabled", "disabled");
                    }

                    if (list.length == 0) {
                        select.attr("disabled", "disabled");
                        return;
                    }
                    let first_item = '<option value="">**select an option**</option>';
                    select.append(first_item);


                    list.forEach(option => {
                        let item = "<option value='" + option.value + "'>" + option.name + "</option>";
                        select.append(item);
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
                console.log(data);
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
            url, method: "POST", data, success: function(res) {
                console.log(res);

                if (res !== null && JSON.parse(res)) {
                    res = JSON.parse(res);

                    if (res.success) {
                        target.remove();
                        step.modal("success", res.message);
                    }
                    else if (res.error) {

                        step.modal("error", res.error.message);
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
            url, method: "POST", data: null, success: function(res) {
                console.log(res);

                if (res !== null && JSON.parse(res)) {
                    res = JSON.parse(res);

                    if (res.success) {

                        object.remove();
                        step.modal("success", res.message);

                        $("[api-variation-creator]").trigger("blur");

                    }
                    else if (res.error) {

                        step.modal("error", res.error.message);
                    }

                    return;
                }
            }
        })


    });

    function activate_button_actions()
    {
        setTimeout(function(){
    
            $('[api-edit]').click(function(){
        
                let connector = $(this).attr("api-edit");
                let url = $(this).attr("href");
    
                target_id = $(this).attr("api-item-id");
        
                $.ajax({
                    url, method:"GET", data:{}, success: function(res) {
                        console.log(res);
        
                        if (res !== null && JSON.parse(res)) {
                            res = JSON.parse(res);
        
                            $('[api-edit-role="'+ connector +'"]').each(function(){
                                let key;
    
                                if($(this).attr("api-field-key")) { 
                                    key = $(this).attr("api-field-key"); 
                                    $(this).val(res[key]);
                                }
    
                                if($(this).attr("api-text-key")){
                                    key = $(this).attr("api-text-key");
                                    $(this).html(res[key]);
                                }
    
                            });
                        }
                    }
                });
        
            });
    
            $('[api-view]').click(function(){
        
                let connector = $(this).attr("api-view");
                let url = $(this).attr("href");
        
                $.ajax({
                    url, method:"GET", data:{}, success: function(res) {
                        console.log(res);
        
                        if (res !== null && JSON.parse(res)) {
                            res = JSON.parse(res);
        
                            $('[api-view-role="'+ connector +'"]').each(function(){
                                let key;
    
                                if($(this).attr("api-field-key")) { 
                                    key = $(this).attr("api-field-key"); 
                                    $(this).val(res[key]);
                                }
    
                                if($(this).attr("api-text-key")){
                                    key = $(this).attr("api-text-key");
                                    $(this).html(res[key]);
                                }
        
                            });
                        }
                    }
                });
        
            });
    
            $('[api-delete]').click(function(){
        
                let connector = $(this).attr("api-delete");
                let url = $(this).attr("href");
        
                target_id = $(this).attr("api-item-id");
    
                $.ajax({
                    url, method:"GET", data:{}, success: function(res) {
                        console.log(res);
        
                        if (res !== null && JSON.parse(res)) {
                            res = JSON.parse(res);
        
                            $('[api-delete-role="'+ connector +'"]').each(function(){
                                let key;
                                
                                if($(this).attr("api-field-key")) { 
                                    key = $(this).attr("api-field-key"); 
                                    $(this).val(res[key]);
                                }
    
                                if($(this).attr("api-text-key")){
                                    key = $(this).attr("api-text-key");
                                    $(this).html(res[key]);
                                }
        
                            });
                        }
                    }
                });
        
            });
    
        }, 1000);
    }

    activate_button_actions();

    if($("#addSkeletonBtn2"))
    {
        let skeleton = "";
        setTimeout(function(){ skeleton = $("#skeleton").html()}, 2000);
        
        $("#addSkeletonBtn2").click(function(){
            $("#skeletonForm").append('<div class="col-md-12 mt-1 pt-2 bordered-top">'+skeleton+"</div>");
            variation_selection();
            variation_creator();
            rni++;
        });

    }

    function variation_creator()
    {
        $("[api-variation-creator]").blur(function(){
    
            index = 0;
            variations = [];
    
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
                        if(value.indexOf(",") > -1) { value = value.replace(" ", "").split(",") } 
                        else { value = Array(value.replace(" ","")); }
    
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
            
            create_variations_combined_list(variations);
    
        });

    }

    function create_variations_combined_list(variations) {
        // console.log(variations);

        variations = JSON.stringify(variations);

        price = $("#Price").val(); discount = $("#Discount").val();
        if(Number(discount) > 0) { price = $("#DiscountPrice").val(); }
        
        $.ajax({
            url: "/a/product/create_variations_list_options", method: "POST", data: {variations, price},
            success: function(resp) {
                // console.log(resp);
                $("[api-variation-list]").removeClass("hide").html(resp);
            }
        });

    }

    variation_creator();

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

    setTimeout(function () { $("#table").DataTable() }, 1000);

    $('[api-order-call]').click(function(){
        var id = $(this).attr("api-order-call");
        var mode = $(this).attr("api-mode");
        url = "/api/a/" + mode + "-order";
        $.ajax({
            url, method: "POST", data: {id}, success: function(resp) {
                console.log(resp);
                if(resp != null && JSON.parse(resp)) {
                    resp = JSON.parse(resp);
                    if(resp.success) {
                        $("body").append('<div class="toast-notification success"><h6 class="text-white"><b>Successfull!</b></h6><p>'+ resp.message + '</p></div>');
                    }
                    else if(resp.error) {
                        $("body").append('<div class="toast-notification error"><h6 class="text-white"><b>Error Occured!</b></h6><p>'+ resp.error.message + '</p></div>');
                    }

                    $(".toast-notification").fadeOut(6000);
                }
            }
        });
    });

    $('[api-approve-product]').click(function(){
        target = $(this);
        product = $(this).attr("api-approve-product");
        $.ajax({
            url: "/api/a/approve-product", method: "POST", data: {product}, success: function(resp) {
                console.log(resp);
                if(resp != null && JSON.parse(resp)) {
                    resp = JSON.parse(resp);
                    if(resp.success) {
                        step.modal("success", resp.message);
                        target.hide();
                        target.siblings(".disapprove").show();
                    }
                    else if(resp.error) {
                        step.modal("error", resp.error.message );

                    }
                }
            }
        });

    }); 

    $('[api-disapprove-product]').click(function(){
        target = $(this);
        product = $(this).attr("api-disapprove-product");
        $.ajax({
            url: "/api/a/disapprove-product", method: "POST", data: {product}, success: function(resp) {
                console.log(resp);
                if(resp != null && JSON.parse(resp)) {
                    resp = JSON.parse(resp);
                    if(resp.success) {
                        step.modal("success", resp.message);
                        target.hide();
                        target.siblings(".approve").show();
                    }
                    else if(resp.error) {
                        step.modal("error", resp.error.message );

                    }
                }
            }
        });

    }); 

    $('[api-hide-product]').click(function(){

        target = $(this);
        product = $(this).attr("api-hide-product");
        $.ajax({
            url: "/api/a/hide-product", method: "POST", data: {product}, success: function(resp) {
                console.log(resp);
                if(resp != null && JSON.parse(resp)) {
                    resp = JSON.parse(resp);
                    if(resp.success) {
                        step.modal("success", resp.message);
                        target.hide();
                        target.siblings(".show-btn").show();
                    }
                    else if(resp.error) {
                        step.modal("error", resp.error.message );
                    }
                }
            }
        });

    });

    $('[api-show-product]').click(function(){
        
        target = $(this);
        product = $(this).attr("api-show-product");
        $.ajax({
            url: "/api/a/show-product", method: "POST", data: {product}, success: function(resp) {
                console.log(resp);
                if(resp != null && JSON.parse(resp)) {
                    resp = JSON.parse(resp);
                    if(resp.success) {
                        step.modal("success", resp.message);
                        target.hide();
                        target.siblings(".hide-btn").show();
                    }
                    else if(resp.error) {
                        step.modal("error", resp.error.message );
                    }
                }
            }
        });

    });

    $(".toast-notification").fadeOut(6000);
    
})();