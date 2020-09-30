(function(){

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

})();