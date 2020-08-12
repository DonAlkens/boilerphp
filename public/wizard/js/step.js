(function(){

    var stepLength = () => {
        return $('[step-id]').length;
    }


    function validateCurrentStep(current) {

        var valid = true, 
        fields = $('[step-id="'+current+'"]').children().find('[step-field-required="true"]');
        fields.each(function(){
            if($(this).val() === "") {valid = false;}
        })
        
        return valid;
    }

    var currentIndex = (nextObject) => {
        return nextObject.attr("step-current");
    }

    function nextAction() {
        $(".step-next").click(function(){
            let current = currentIndex($(this));
            
            if(validateCurrentStep(current)) {
                stepCompleted(current); next(current);
            } 
            else {
                stepError(current);
            } 
        });
    }

    function nextStep(current, index) {
        $('[step-id="'+current+'"]').fadeOut().removeClass("active").removeClass("show").addClass("hide");
        $('[step-id="'+index+'"]').removeClass("hide").fadeIn().attr("class","show active");

        setNextIndex(index);
        activeBadge(index);
    }

    function setNextIndex(index) {
        if(index < stepLength()){
            submitButton(false);
        }
        $(".step-next").attr("step-current", index);
    }


    function next(current) {
        let index = Number(current) + 1;
        if(index == stepLength()) {
            submitButton(true);
        }

        if(index <= stepLength()) {
            if(index > 1) {setPreviousButton(index);}
            nextStep(current, index);
        }
    }



    function previousAction() {
        $(".step-previous").click(function(){
            let index = $(this).attr("step-page");
            previous(index);
        });
    }

    function previous(index) {

        index = Number(index);
        let current = index + 1;
        $('[step-id="'+current+'"]').fadeOut().removeClass("active").removeClass("show").addClass("hide");
        $('[step-id="'+index+'"]').removeClass("hide").fadeIn().attr("class","show active");

        setPreviousButton((index - 1));
        setNextIndex(index);
        resetBadge((index + 1));
        activeBadge((index))
    }

    function resetBadge(index) {
        $('[step-badge="'+index+'"]').removeClass(["active", "completed", "error"]);
    }

    function activeBadge(index) {
        $('[step-badge="'+index+'"]').removeClass(["error","completed"]).addClass("active");
    }

    function stepCompleted(current) {
        $('[step-badge="'+current+'"]').removeClass("active").removeClass("error").addClass("completed");
    }

    function stepError(current) {
        $('[step-badge="'+current+'"]').addClass("error");
    }

    function submitButton(state) {
        if(state){
            $(".step-next").hide(); $(".step-submit").show();
        }
        else {
            $(".step-next").show(); $(".step-submit").hide();
        }
    }

    function setPreviousButton(index) {
        if(index == 0) {
            $(".step-previous").removeAttr("step-page").hide();
        } 
        else {
            $(".step-previous").attr("step-page", index).show();
        } 

    }

    function initActions(){
        $(".step-submit").hide();
        $(".step-previous").hide();
        $(".step-next").attr("step-current", "1");

    }

    function init() {
        initActions();
        nextAction();
        previousAction();
    }

    var Step = () => {init()}

})();