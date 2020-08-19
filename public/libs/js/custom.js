(function(){

    $(document).ready(function() {
        $('#Description').summernote({
            height: 300
        });
    });
    
    if($("#addSkeletonBtn"))
    {
        let skeleton = $("#skeleton").html();
        
        $("#addSkeletonBtn").click(function(){
            $("#skeletonForm").append('<div class="col-md-6">'+skeleton+"</div>");
            rni++;
        });
    }

    if($("#addSkeletonBtn2"))
    {
        let skeleton = $("#skeleton").html();
        
        $("#addSkeletonBtn2").click(function(){
            $("#skeletonForm").append('<div class="divider"></div><div class="col-md-12">'+skeleton+"</div>");
            rni++;
        });
    }

})();