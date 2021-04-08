(function(){

    $(document).ready(function(){
        $(".preloader").fadeOut();
    });

    $("#useSearch").click(function () {
        var collection = $("#s_col").val();
        var keywords = $("#q").val();
        var page = "/search";
        var hast_tags = false;

        if (keywords != "") 
        {
            keywords = keywords.split(" ").join('+');

            if(keywords.indexOf("#") > -1){
                hast_tags = true;
                keywords.replace("#", "");
            }

            if (collection != "") {

                page += "/" + collection;
            }
            page += "?q=" + keywords;

            if(hast_tags == true) {
                page += "&hast_tags=1"
            }

            window.location.href = page;
        }
    });

    $("#means").change(function()
    {
        var value = $(this).val();
        if(value == "Pick-up") {
            $(".pick-date").fadeIn().removeClass("hide");
            $("#pickup_day").attr("validate-field", "true").attr("validation-message", "Kindly select pick-up date.");
        }
        else {
            $(".pick-date").fadeOut().addClass("hide");
            $("#pickup_day").removeAttr("validate-field").removeAttr("validation-message");
        }
    });

    $(".vn-item-a").click(function (e) {
		e.preventDefault();

		$(".vn-item").removeClass("active");
		$(this).parent(".vn-item").addClass("active");

		var step = $(this).attr("href");
		var offset = $(step).offset();
		window.scrollTo({
			top: offset.top,
			left: 0,
			behavior: 'smooth'
		});

	});

	$(".vn-sub-item-a").click(function (e) {
		e.preventDefault();

		$(".vn-sub-item-a").removeClass("active");
		$(this).addClass("active");

		var step = $(this).attr("href");
		var offset = $(step).offset();
		window.scrollTo({
			top: offset.top - 100,
			left: 0,
			behavior: 'smooth'
		});
	});

	$(".vc-click").click(function (e) {
		e.preventDefault();

		var step = $(this).attr("href");
		var offset = $(step).offset();
		window.scrollTo({
			top: offset.top - 100,
			left: 0,
			behavior: 'smooth'
		});
	});

    let step = new Step();
    step.init();

    // banner slider
    var sliderCarousel = $('.slideshow');
    sliderCarousel.owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        autoplay: true,
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
        autoplayTimeout: 7000,
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
        loop: false,
        dots: true,
        nav: true,
        autoplay: false,
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
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

    // deals-nsv
    var deals_nsv = $('.deals-nsv');
    deals_nsv.owlCarousel({
        loop: false,
        dots: true,
        nav: true,
        autoplay: false,
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
        responsive: {
            0: {
                items: 3
            },
            768: {
                items: 4
            },
            960: {
                items: 5
            },
            1200: {
                items: 7
            },
            1920: {
                items: 8
            }
        }
    }); 


    // cl-listing
    var cl_listing = $('.cl-listing');
    cl_listing.owlCarousel({
        loop: false,
        dots: true,
        nav: false,
        autoplay: false,
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
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
                items: 4
            },
            1920: {
                items: 5
            }
        }
    }); 


    var segCarousel = $('.segments');
    segCarousel.owlCarousel({
        loop: false,
        dots: true,
        nav: true,
        autoplay: false,
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
        autoplayTimeout: 6000,
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
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
        autoplayTimeout: 6000,
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
        loop: false,
        dots: true,
        nav: true,
        autoplay: false,
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
        autoplayTimeout: 6000,
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
                items: 4
            },
            1920: {
                items: 5
            }
        }
    }); 

    var salesCarousel = $('.sss-scroll-2');
    salesCarousel.owlCarousel({
        loop: false,
        dots: true,
        nav: true,
        autoplay: false,
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
        autoplayTimeout: 6000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 2
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
                items: 4
            }
        }
    }); 

    var vnCarousel = $('.vn-slideshow');
    vnCarousel.owlCarousel({
        loop: true,
        dots: true,
        nav: false,
        autoplay: true,
        navText: ['',''],
        autoplayTimeout: 6000,
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


    // vd-test-slideshow
    var vnCarousel = $('.vd-test-slideshow');
    vnCarousel.owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        autoplay: true,
        navText: ['<i class="flaticon2-back"></i>','<i class="flaticon2-next"></i>'],
        autoplayTimeout: 6000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 1
            },
            960: {
                items: 3
            },
            1200: {
                items: 3
            },
            1920: {
                items: 3
            }
        }
    });

    $(".menu-btn").click(function(){
        $(".menu-bar").toggleClass("menu-active");
        // $(".menu-inner").toggleClass("fadeInLeft animated");
    });

    $(".vd-md-menu").click(function(){
        $(".vn-nav-wrapper").slideToggle();
    });

    $(".menu-bar").click(function(event){
        if(event.target == $(this)[0]) {
            $(".menu-bar").removeClass("menu-active");
            // $(".menu-inner").removeClass("fadeInLeft animated");
        }
    });


    window.onscroll = function() {fixedNavbar()};

    var navbar = document.getElementById("header-middle");
    var fixNav = navbar.clientHeight + 150;
    var mkpSideBar = 389;

    function fixedNavbar() {
        if(window.pageYOffset >= fixNav) {   
            $('#header-middle').addClass('header-fixed fadeInDown animated');
            if(window.innerWidth > 480) 
            {
                $(".header-navbar").hide();
                $(".menu").show();
            }
        } else {
            $('#header-middle').removeClass('header-fixed fadeInDown animated');
            if(window.innerWidth > 480) {
                $(".header-navbar").show();
                $(".menu").hide();
            }
        }

        if(window.pageYOffset >= mkpSideBar) {   
            $('#vn-guides-sidebar').addClass('fixed');
        } else {
            $('#vn-guides-sidebar').removeClass('fixed');
        }
    }

    $(".tg-subs").click(function(){
        $(this).toggleClass("mdi-minus")
        $(this).siblings(".sub-items-list").toggleClass("slide");
    });

    $(".mobile-filter").click(function(){$(".sidebar").fadeIn();});
    $(".close-filter").click(function(){$(".sidebar").fadeOut();});

    $(".selectpicker").selectpicker();

    $('[check-radio]').click(function() {
        $('[check-radio-tab]').addClass("hide").fadeOut();
        target = $(this).attr("check-radio");
        if($(this).prop("checked") == true) { 
            $(target).fadeIn().removeClass("hide"); 
        }
    });

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
                if(message != "" && message != null) {
                    $(this).css("border-color", "red").after('<small class="text-danger" validation-result="true">' + message + '</small>');
                }
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

    $(".mSearchToggle2").click(function(){
        $(".search").addClass("show fadeInDown animated");
    });

    $("body").click(function(event){
        if(event.target != $(".uac-menu-toggle")[0]) {
            $(".uac-menu-list").removeClass("show fadeIn animated"); 
        }

        if(window.innerWidth < 500) {
            if($(".mSearch").index(event.target) < 0 && event.target != $(".mSearchToggle2")[0]) {
                $(".search").removeClass("show fadeInDown animated"); 
            }
        }
    });

    $(".it-toggle").click(function(){
        $('.checkout-items').slideToggle();
    });

    $("#diffent_address").change(function(){

        if($(this).prop("checked") == true) {
            $(".address-form").slideDown();
        } 
        else {
            $(".address-form").slideUp();
        }

    });

    $(".gateway, .shaddress").change(function(){

        $(".option-card").removeClass("selected");

        if($(this).prop("checked") == true) {
            $(this).parent(".option-card").addClass("selected");
            $(this).parent(".form-group").parent(".option-card").addClass("selected");

            if($(this).attr("id") == "diffent_address") {

                $("[step-toggle]").each(function(){
                    message = $(this).attr("step-toggle");
                    $(this).attr("step-field-required", "true");
                    $(this).attr("step-validation-message", message);
                });

                $(".address-form").slideDown();
            }
            else 
            {
                $("[step-toggle]").each(function(){
                    $(this).removeAttr("step-field-required");
                    $(this).removeAttr("step-validation-message");
                });

                $(".address-form").slideUp();
            }
        } 
    });

    $(".step-form").submit(function(event)
    {
        if($("#password").val() != $("#confirm_password").val()) 
        {
            event.preventDefault();
            $("#confirm_password").after('<span class="text-danger step-validation-message" step-validation-result="true">Password does not match</span>');
        } else {
            $("#confirm_password").siblings(".step-validation-message").remove();
        }
    });

    $(".navbar-btn").click(function(){
        $(".mobile-sidebar-nav-wrapper").toggleClass("show animated fadeIn");
        $(".mobile-sidebar-navbar").toggleClass("fadeInLeft animated");
    });

    $(".mobile-sidebar-nav-wrapper").click(function(event){
        if(event.target == $(this)[0]) {
            $(this).removeClass("show animated fadeIn");
            $(".mobile-sidebar-navbar").removeClass("fadeInLeft animated");
        }
    });

    $(".mm-close").click(function(){
        $(".mobile-sidebar-nav-wrapper").removeClass("show animated fadeIn");
        $(".mobile-sidebar-navbar").removeClass("fadeInLeft animated");
    });

    $(".pop-message").fadeOut(10000);

    $(".pop-close").click(function(){
        $(".pop-message").hide();
    });

})();