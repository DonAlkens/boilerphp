(function(){

    $(document).ready(function() {
        $('#Description').summernote({
            height: 300
        });
    });
    
    if($("#addSkeletonBtn"))
    {
        let skeleton = "";
        setTimeout(function(){
            skeleton = $("#skeleton").html()
        }, 2000);
        
        $("#addSkeletonBtn").click(function(){
            $("#skeletonForm").append('<div class="col-md-6">'+skeleton+"</div>");
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
            $("#skeletonForm").append('<div class="divider"></div><div class="col-md-12">'+skeleton+"</div>");
            rni++;
        });
    }

})();