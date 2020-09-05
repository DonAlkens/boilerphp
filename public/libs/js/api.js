(function () {

    var step = new Step();
    step.init();

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
            $("#GalleryBox").removeClass("hide");
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

                            if (!edit && form.attr("class") != "add-product") {
                                imageArr = [];
                                $("input, textarea").val("");
                                $("#img-preview").html("");
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
            if (action == "edit") {
                buttons += '<a href="' + links[i] + id + '" api-edit="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-gradient-success btn-sm">' + action + '</a>';
            }
            
            if (action == "view") {
                buttons += '<a href="' + links[i] + id + '" api-view="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-gradient-info btn-sm" >' + action + '</a>';
            }
            
            if (action == "delete") {
                buttons += '<a href="' + links[i] + id + '" api-delete="'+ modalName.toLowerCase() +'" api-item-id="'+ id +'"';
                if(modal && modalName != "") {  buttons +=  modal(action, modalName) }
                buttons += 'class="btn btn-gradient-danger btn-sm">' + action + '</a>';
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


        if (actions.indexOf(",") > -1) { actions = actions.split(","); } else { actions = array(actions); }
        if (links.indexOf(",") > -1) { links = links.split(","); } else { links = array(links); }

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
                        let card = '<div class="'+ column_size +'">';

                        card += '<div class="product-img-outer">';
                        card += '<a href="/a/products/view/'+ item.id +'"><img class="product_image" src="'+ "/src/images/"+ item.image +'" alt="'+ item.name +'"></a>';
                        card += '</div>';

                        card += '<p class="product-title"><a href="/a/products/view/'+ item.id +'">'+ item.name +'</a></p>';
                        
                        // card += '<div class="d-flex justify-content-between">';
                        card += '<p class="product-price">'+ item.price +'</p>';
                        card += '<p class="product-actual-price"> <b>Discount: </b>'+ Math.round(item.discount) +'%</p>';
                        // card += '</div>';

                        card += '<p class="product-description"> <b> Category: </b>'+ item.collection + " / " + item.category +'.</p>';

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
                    if(value.indexOf(",") > -1) { value = value.split(",");} else { value = Array(value); }

                    if(index == 0) {

                        value.forEach(name => { 
                            variation = {name};
                            variations.push(variation);
                        });
                    }
                    else 
                    {
                        variation_holder = []
                        counter = 0;
                        for (let i = 0; i < variations.length; i++) {
                            
                            for (let j = 0; j < value.length; j++) {

                                variation = {
                                    name: variations[i].name + " / " + value[j]
                                };
                                variation_holder[counter] = variation;

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


    function create_variations_combined_list(variations) {
        console.log(variations);
        $.ajax({
            url: "/a/product/create_variations_list_options", method: "POST", data: {variations},
            success: function(resp) {
                $("[api-variation-list]").fadeIn().html(resp);
            }
        })

    }

    setTimeout(function () { $(".table").DataTable() }, 1000);

})();