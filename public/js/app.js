(function(){
    // banner slider
    var sliderCarousel = $('.slideshow');
    sliderCarousel.owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        autoplay: true,
        navText: ['<i class="pe-7s-angle-left"></i>','<i class="pe-7s-angle-right"></i>'],
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 1
            },
            960: {
                items: 1
            },
            1200: {
                items: 1
            },
            1920: {
                items: 1
            }
        }
    }); 

    // collections
    var collectionsCarousel = $('.collections');
    collectionsCarousel.owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        autoplay: true,
        navText: ['<i class="pe-7s-angle-left"></i>','<i class="pe-7s-angle-right"></i>'],
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2
            },
            768: {
                items: 3
            },
            960: {
                items: 4
            },
            1200: {
                items: 6
            },
            1920: {
                items: 6
            }
        }
    }); 

    //super-sales-items
    var salesCarousel = $('.sss-scroll');
    salesCarousel.owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        autoplay: true,
        navText: ['<i class="pe-7s-angle-left"></i>','<i class="pe-7s-angle-right"></i>'],
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2
            },
            768: {
                items: 3
            },
            960: {
                items: 4
            },
            1200: {
                items: 5
            },
            1920: {
                items: 6
            }
        }
    }); 

    $(".menu-btn").click(function(){
        $(".menu-bar").toggleClass("menu-active");
        $(".menu-inner").toggleClass("inner-active");
    });

    $(".menu-bar").click(function(event){
        if(event.target == $(this)[0]) {
            $(".menu-bar").toggleClass("menu-active");
            $(".menu-inner").toggleClass("inner-active");
        }
    });

    window.onscroll = function() {fixedNavbar()};

    var navbar = document.getElementById("header-middle");
    var fixNav = navbar.clientHeight;

    function fixedNavbar() {
        if(window.pageYOffset >= fixNav) {   
            $('#header-middle').addClass('header-fixed fadeInDown animated');
        } else {
            $('#header-middle').removeClass('header-fixed fadeInDown animated');
        }
    }

    $(".selectpicker").selectpicker();

})();