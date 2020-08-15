(function(){
    
    if($("#addSkeletonBtn"))
    {
        let skeleton = $("#skeleton").html();
        
        $("#addSkeletonBtn").click(function(){
            $("#skeletonForm").append('<div class="col-md-6">'+skeleton+"</div>");
            rni++;
        });
    }

    $("#freeShipment").change(function(){
        var state = $(this).prop("checked");
        (state) ? 
        $("#shipping_price").attr("disabled","disabled").removeAttr("req").val("")
        : $("#shipping_price").removeAttr("disabled").attr("req","true");
    });

    $(".tab-h-btn").click(function(){
        var ref = $(this).attr("ref");
    });

    $(".del").click(function(){
        var _target = $(this).attr("tag");
        $("#c-message").html("Are you sure you want to delete this item ?");
        $("#c-del").attr("href", "?del="+_target);
        $("#confirm").fadeIn();
    });

    $(".size").click(function(){
        var c = $(this).children("input");
        if(c.prop("checked") == true) {

            $(".size").each(function(){
                $(this).removeClass("active");
                $(this).children("input").removeAttr("checked");
            });

            $(this).addClass("active");
        } else {
            $(this).removeClass("active");
        }
    });

    $(".qt").click(function(){
        var type = $(this).attr("data-type");
        var quantity = $("#quantity");
        var qt = Number(quantity.val());
        if(type == "plus") {
            quantity.val(qt + 1);
        } else {
            if(qt > 1) {
                quantity.val(qt - 1);
            }
        }
    });

    $(".remove").click(function(){
        var target = $(this).attr("item");
        $("#rm-item").val(target);
        $("#rm-confirm").fadeIn();
    });

    $(".cm-overlay").click(function(event) {
        if(event.target.className == "cm-overlay") {
            $(this).fadeOut();
        }
    });

    $("#address").click(function(){
        let mode = $(this).prop("checked");
        if(mode == true) {
            $("#different-address-box").slideDown();
            $(".ship").each(function(){
                $(this).attr("req", "true");
            });
        } else {
            $("#different-address-box").slideUp();
            $(".ship").each(function(){
                $(this).removeAttr("req");
            });
        }
    });

    $("#remember").click(function(){
        let mode = $(this).prop("checked");

        if(mode == true && $(this).attr("frt") != undefined) {
            $("#create-password").slideDown();
            $(".pass").each(function(){
                $(this).attr("req", "true");
            });
        } else {
            $("#create-password").slideUp();
            $(".pass").each(function(){
                $(this).removeAttr("req");
            });
        }
    });

    $(".fade-alert").fadeOut(10000);
    $(".qvc-btn").click(function(){
        $(".fancybox-overlay").fadeOut();
    })

})();