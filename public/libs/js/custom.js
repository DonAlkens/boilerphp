(function(){

    var imgSlides = $('.images-slides');
    imgSlides.owlCarousel({
        loop: false,
        dots: false,
        nav: true,
        autoplay: false,
        navText: ['<i class="pe-7s-angle-left"></i>','<i class="pe-7s-angle-right"></i>'],
        autoplayTimeout: 4000,
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