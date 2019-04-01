window.onscroll = function(e){
    if(window.innerWidth > 480){
        let navBar = $("#nav-bar");
        if(window.scrollY > 130) {
			navBar.css({"background-color":"#fff","box-shadow":"0px 0px 6px 2px #cfd2d470"});

			$(".menu-bar").css("color","#000");
			$(".up").css({"border-color":"#021d4c","color":"#021d4c"});
        } else {
			navBar.css({"background":"none","box-shadow":"none"});
			$(".menu-bar").css("color","#fff");
			$(".up").css({"border-color":"#fff","color":"#fff"});
        }
    }
}

$("input").attr("autocomplete","off");

$("#ticon").click((e)=> {
    var hLW = $(".header-link-wrapper");
    let h = $(".header-link-wrapper").height();
    if(h > 0) {
        hLW.css("height","0px");
        return;
    } 
    hLW.css("height","auto");
});


$(".toggle-bars").click((e)=> {
    $(".toggle-bars").toggleClass("toggle-active");
    $(".dashboard-side-menu").toggleClass("slide-in-menu");
});

$(".overlay-popup").click(function(){
    $(this).fadeOut("fast");
});


let i = 0;
$("#showCreateForm").click(function(){
   if((i % 2) == 0){
       $(".createProjectForm").slideDown(80);
   } else {
        $(".createProjectForm").slideUp(80);
   }
    i++;
});

$(".signin").click(function(e){
	$(".form-control").each(function(){
		$(this).css("border-color","#dcdcdc").siblings(".text-danger").html("");
		if($(this).val() == "") {
			e.preventDefault();
			$(this).css("border-color","red").siblings(".text-danger").html($(this).attr("id") + " cannot be empty");
		}
	});
});

$(".signup").click(function(e){
	// var agree = $(".check-box");
    // agree.parent(".mt-3").removeClass("alert alert-danger");
    
	 var i = 0;
	$(".form-control").each(function(){
		$(this).css("border-color","#dcdcdc").siblings(".text-danger").html("");

		if($(this).val() == "") {
			e.preventDefault();
			$(this).css("border-color","red").siblings(".text-danger").html("this field is required");
			i++;
		}
	});

	var email = $("#email"),
		password = $("#password"), 
		conf_password = $("#cpassword");

	if(Boolean(email.val())) {
        let regex = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

		if(!email.val().match(regex)) {
			e.preventDefault();
			email.siblings(".text-danger").html("invalid email address");
		}
	}

	if(Boolean(password.val()) && password.val().length < 8) {
		e.preventDefault();
		password.siblings(".text-danger").html("password to short - minimum characters should be 8");
	}

	if(Boolean(password.val()) && Boolean(conf_password.val())) {
		if(password.val() !== conf_password.val()) {
			e.preventDefault();
			conf_password.siblings(".text-danger").html("password not matched");
		}
	}


	// if(agree.prop("checked") !== true && i == 0) {
	// 	e.preventDefault();
	// 	agree.parent(".mt-3").addClass("alert alert-danger");
	// }
});

$("#newProBtn").click(function(e){
    var title = $("#title");
    var regex = /[a-z]$/;
    if(title.val() !== "") {
        if(!title.val().match(regex)){
            title.siblings(".text-danger")
                .html("invalid input. special characters, spaces, capital letters, symbols are not allowed in this field."); 
            e.preventDefault();
        }
    } else {
        title.siblings(".text-danger").html("*plase enter the project title in the field above");
        e.preventDefault();
    }
});

$('#saveTemplate').click(function(e){
	$(".form-control").each(function(){
		$(this).css("border-color","#dcdcdc").siblings(".text-danger").html("");
		if($(this).val() == "") {
			e.preventDefault();
			$(this).css("border-color","red").siblings(".text-danger").html($(this).attr("id") + " cannot be empty");
		}
	});
});

var editorHeader = document.querySelector("#editorHeader");
var pallete = document.querySelector(".editor-pallete");
editorHeader.addEventListener("dragstart", (e) => {
	
});

var pageX, pageY;

editorHeader.addEventListener("drag", (e)=>{
	if(e.pageX > 0 && e.pageY > 0){
		pageX = e.pageX;
		pageY = e.pageY;
	}
});

editorHeader.addEventListener("dragend",() => {
	console.log(pageX, pageY);
	pallete.style.left = pageX + "px";
	pallete.style.top = pageY + "px";
});


$(".roll").click(function(){
	
});