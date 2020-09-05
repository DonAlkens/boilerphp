(function(){

    var step = new Step(), selected = [];

    $(document).ready(function() {
        $('#Description').summernote({ height: 300 });
    });
    
    if($("#addSkeletonBtn"))
    {
        let skeleton = "";
        setTimeout(function(){
            skeleton = $("#skeleton").html();
        }, 1000);
        
        $("#addSkeletonBtn").click(function(){
            $("#skeletonForm").append('<div class="col-md-6 mb-3" cascade="true">'+ skeleton + '</div>');
            rni++;
        });
    }

    if($("#addSkeletonBtn2"))
    {
        let skeleton = "";
        
        setTimeout(function(){
            skeleton = $("#skeleton").html()
        }, 2000);
        
        $("#addSkeletonBtn2").click(function(){
            $("#skeletonForm").append('<div class="col-md-12 mt-1 pt-2 bordered-top">'+skeleton+"</div>");
            variation_selection();
            rni++;
        });
    }

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

})();