(function(){
    
    if($("#addVarientBtn"))
    {
        $("#addVarientBtn").click(function(){
            let skeleton = $("#variationSkeleton").html();
            $("#variationForm").append('<div class="row">'+skeleton+"</div>");
        });
    }

    $("#freeShipment").change(function(){
        var state = $(this).prop("checked");
        (state) ? $("#shippingPrice").attr("disabled","disabled") : $("#shippingPrice").removeAttr("disabled");
    });
})();