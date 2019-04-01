document.addEventListener("submit",(e)=> {
    e.preventDefault();
});

(function(element){
    
    $(".hide-pallete").click(function(){
        $(".editor-pallete").css("right","-21%");
    });
    
    $(".content").click(function(e){
        element = e.target;

        if(element.nodeName === "A" || 
            element.nodeName === "BUTTON") {
                e.preventDefault();
        }
        $(".editor-pallete").css("right","30px");
    
        
    
        palleteConfig(element);
    
        $("#html").keyup(function(){
            element.textContent = $(this).val();
        });

        $("#html").blur(function(){
            element = null;
        });
    
    
    });
    
    function palleteConfig(e){
        var boxes = ['DIV','SECTION','HEADER','FOOTER','BODY','I'];
        if(boxes.indexOf(e.nodeName) < 0){
            $("#html").val(e.textContent);
        }
    }

})();





