(function(){

    let step = new Step();
    step.init();

    // banner slider
    var sliderCarousel = $('.slideshow');
    sliderCarousel.owlCarousel({
        loop: true,
        dots: false,
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
    var collsCarousel = $('.collections');
    collsCarousel.owlCarousel({
        loop: true,
        dots: false,
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
        // $(".menu-inner").toggleClass("fadeInLeft animated");
    });


    $(".menu-bar").click(function(event){
        if(event.target == $(this)[0]) {
            $(".menu-bar").removeClass("menu-active");
            // $(".menu-inner").removeClass("fadeInLeft animated");
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

    $(".mobile-filter").click(function(){$(".sidebar").fadeIn();});
    $(".close-filter").click(function(){$(".sidebar").fadeOut();});

    $(".selectpicker").selectpicker();

    $("[app-rating]").each(function(){
        rate = $(this).attr("app-rating");
        half = 0;
        if(rate.indexOf(".") > -1) {
            get_half = rate.split(".");
            rate = get_half[0];
            half = get_half[1];
        }

        rate = Number(rate); half = Number(half);

        for(i = 0; i < 5; i++) {
            if(i < rate) { $(this).append('<i class="icofont-star rating full"></i>'); }
            else if(half > 0) { $(this).append('<i class="icofont-star rating half-'+half+'"></i>');half = 0; }
            else { $(this).append('<i class="icofont-star rating"></i>'); }
        }
    });


    // Rate Action
    var star_clicked = false;
    var star_number = 0;

    $(".star").click(function(){
        star_clicked = true;
        var _trg = $(this).attr("trg");
        star_number = _trg;

        $(".star").each(function(){
            $(this).css("color","#dcdcdc");
        });

        var i = 1;
        $(".star").each(function(){
            if(i > star_number) {return null;}
            $(this).css("color","#a356f0");
            i++;
        });
        $("#rate").val(star_number);
    });

    $(".star").on("mouseover",function(){
        star_clicked = false;

        var _trg = $(this).attr("trg");
        var i = 1;

        $(".star").each(function(){
            if(i > _trg) {return null;}
            $(this).css("color","#a356f0");
            i++;
        });

    });

    $(".star").on("mouseout",function(){

       if(!star_clicked){
           $(".star").each(function(){
               $(this).css("color","#dcdcdc");
           });
       }
       
       if(!star_clicked || start_clicked && star_number > 0) {
           if(star_number > 0) {
                $("#rate").val(star_number);
                var i = 1;
                $(".star").each(function(){
                    if(i > star_number) {return null;}
                    $(this).css("color","#a356f0");
                    i++;
                });
           }
       }

    });

    $(".sign-in, .sign-up").click(function(event){
        event.preventDefault();

        var validation = true;

        $('[validation-result="true"]').remove();

        $('[validate-field="true"]').each(function () {
    
            if ($(this).val() == "") {
                var message = $(this).attr("validation-message");
                $(this).css("border-color", "red").after('<small class="text-danger" validation-result="true">' + message + '</small>');
                validation = false;
            }
        });

        if(typeof(agree) != "undefined") {

            if($("#agree").prop("checked") != true) {
                $('[for="agree-result"]').after('<small class="text-danger" validation-result="true"><br>kindly read and agree to complete sign up.</small>');
                
                validation = false;
            }
        }
    
        if (validation) {
            $('[validate-form="true"]').submit();
        }

    });

    $(".uac-menu-toggle").click(function(){
        $(".uac-menu-list").toggleClass("show fadeIn animated");
    });

    $("body").click(function(event){
        if(event.target != $(".uac-menu-toggle")[0]) {
            $(".uac-menu-list").removeClass("show fadeIn animated"); 
        }
    });

    $(".it-toggle").click(function(){
        $('.checkout-items').slideToggle();
    });

})();