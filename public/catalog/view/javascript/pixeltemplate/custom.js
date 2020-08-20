/* responsive menu */
function openNav() {
    $('body').addClass("active");
    document.getElementById("mySidenav").style.width = "280px";
    jquery('#mySidenav').addClass("dblock");
}
function closeNav() {
    $('body').removeClass("active");
    document.getElementById("mySidenav").style.width = "0";
    jquery('#mySidenav').addClass("dnone");
}

 /* loader */
/* Slider Loader*/
$(window).load(function myFunction() {
    $(".s-panel .loader").removeClass("wrloader");
});

//go to top
$(document).ready(function () {
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#scroll').fadeIn();
        } else {
            $('#scroll').fadeOut();
        }
    });
    $('#scroll').click(function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });
});



$(document).ready(function () {
$("#ratep,#ratecount").click(function() {
    $('body,html').animate({
        scrollTop: $(".product-tab").offset().top 
    }, 1500);
});
});

$(document).ready(function () {
$('.dropdown button.test').on("click", function(e)  {
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
});
});


/* dropdown effect of account */
$(document).ready(function () {
    if ($(window).width() <= 767) {
    $('.catfilter').appendTo('.appres');

    $('.dropdown a.account').on("click", function(e) {
        $(this).next('ul').toggle();
        e.stopPropagation();
        e.preventDefault();
    });
}
$('.textbnr').appendTo('.onsale-banner-ap');
$('.news-b').appendTo('.newsc');
});
/* dropdown */

/* sticky header */
  if ($(window).width() > 992) {
 $(document).ready(function(){
      $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.menu-part').addClass('fixed fadeInDown animated');
        } else {
            $('.menu-part').removeClass('fixed fadeInDown animated');
        }
      });
});
};


$(document).ready(function(){
$("#common-home").parent().addClass("home-page");
});



$(document).ready(function(){

    $('.img-thumb').click(function () {

     var src = $(this).attr('src');
     console.log($(this).closest(".product-thumb").find('.js-product-cover').attr('src',src));
});
    $(".heading span").html(function(){
  var text= $(this).text().trim().split(" ");
  var first = text.shift();
  return (text.length > 0 ? "<span class='first-world'>"+ first + "</span> " : first) + text.join(" ");
});
});

