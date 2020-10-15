
var stepLength = () => {
    return $('[step-id]').length;
}

function badgeClick() {
    $('[step-badge]').click(function(){
        index = $(this).attr("step-badge");
        index = Number(index);

        let current = currentIndex($('.step-next')); current = Number(current);
        let change = true;

        if(index > current) {
            for (let i = 1; i < index; i++) {
               if(!validateCurrentStep(i)) { stepError(i); change = false; } 
               else { stepCompleted(i); next(i) } 
            }
            
            if(change) { next((index - 1)); }
        } 
        else if(index < current) {
            
            for (let i = 1; i < current; i++) {
                previous((current - i));
                if((current - i) == index) { break; }
            }

        }




    })
}

function validateCurrentStep(current) {

    var valid = true,
        fields = $('[step-id="' + current + '"]').children().find('[step-field-required="true"]');
    fields.each(function () {
        $(this).css("border-color","#dcdcdc").siblings('[step-validation-result="true"]').remove();
        if ($(this).val() === "") { 
            var message = $(this).attr("step-validation-message");
            $(this).css("border-color","red").after('<span class="text-danger step-validation-message" step-validation-result="true">'+ message +'</span>');
            valid = false; 
        }
    });

    return valid;
}

var currentIndex = (nextObject) => {
    return nextObject.attr("step-current");
}

function nextAction() {
    $(".step-next").click(function () {
        let current = currentIndex($(this));

        if (validateCurrentStep(current)) {
            stepCompleted(current); next(current);
        }
        else {
            stepError(current);
        }
    });
}

function nextStep(current, index) {
    $('[step-id="' + current + '"]').fadeOut().removeClass("active").removeClass("show").addClass("hide");
    $('[step-id="' + index + '"]').removeClass("hide").fadeIn().attr("class", "show active");

    setNextIndex(index);
    activeBadge(index);
}

function setNextIndex(index) {
    if (index < stepLength()) {
        submitButton(false);
    }
    $(".step-next").attr("step-current", index);
}


function next(current) {
    let index = Number(current) + 1;
    if (index == stepLength()) {
        submitButton(true);
    }

    if (index <= stepLength()) {
        if (index > 1) { setPreviousButton(current); }
        nextStep(current, index);
    }
}



function previousAction() {
    $(".step-previous").click(function () {
        let index = $(this).attr("step-page");
        previous(index);
    });
}

function previous(index) {

    index = Number(index);
    let current = index + 1;
    $('[step-id="' + current + '"]').fadeOut().removeClass("active").removeClass("show").addClass("hide");
    $('[step-id="' + index + '"]').removeClass("hide").fadeIn().attr("class", "show active");

    setPreviousButton((index - 1));
    setNextIndex(index);
    resetBadge((index + 1));
    activeBadge((index))
}

function resetBadge(index) {
    $('[step-badge="' + index + '"]').removeClass(["active", "completed", "error"]);
}

function activeBadge(index) {
    $('[step-badge="' + index + '"]').removeClass(["error", "completed"]).addClass("active");
}

function stepCompleted(current) {
    $('[step-badge="' + current + '"]').removeClass("active").removeClass("error").addClass("completed");
}

function stepError(current) {
    $('[step-badge="' + current + '"]').addClass("error");

    stepModal("error", "Some required fields are not filled correctly.")
}

function stepModal(mode, message, iconClass = null) {

    let iconError = (iconClass !== null) ? iconClass : "mdi mdi-alert-circle-outline";
    let iconSuccess = (iconClass !== null) ? iconClass : "mdi mdi-check-circle-outline";
    
    let icon = (mode == "success") 
    ? '<i class="step-icon '+ iconSuccess +' success"></i>' 
    : '<i class="step-icon '+ iconError + '"></i>';

    $("body").append('<div class="step-overlay"><div class="step-modal-box"><div class="text-center">' + icon + '<p class="step-message">' + message + '</p><button class="step-close btn btn-primary">Close</button></div></div></div>');

    closePopUpAction();
}

function stepLoading(state, message = null){
    if(state) {
        $("body").append('<div class="step-overlay text-center" step-loading="true"><div class="step-loading"><div class="d-flex justify-content-center"><div class="spinner-grow text-primary" role="status"><span class="sr-only text-white"></span></div></div><p class="text-center text-white">'+message+'</p></div></div>');
    } 
    else {
        $('[step-loading="true"]').remove();
    }
}

function closePopUpAction() {
    $(".step-close").click(function () {
        $(".step-overlay").fadeOut().remove();
    });
}

function submitButton(state) {
    if (state) {
        $(".step-next").hide(); $(".step-submit").show();
    }
    else {
        $(".step-next").show(); $(".step-submit").hide();
    }
}

function stepSubmit() {
    $(".step-submit").click(function(event){
        current = $('[step-id]').length;
        
        if(validateCurrentStep(current)) {
            submitForm();
        } 
        else {
            event.preventDefault();
            stepError(current);
        }
    });
}

function submitForm() {
    $(".step-form")[0].submit();
}

function setPreviousButton(index) {
    if (index == 0) {
        $(".step-previous").removeAttr("step-page").hide();
    }
    else {
        $(".step-previous").attr("step-page", index).show();
    }

}

function initActions() {
    $(".step-submit").hide();
    $(".step-previous").hide();
    $(".step-next").attr("step-current", "1");

}

function init() {
    initActions();
    badgeClick();
    nextAction();
    previousAction();
    stepSubmit();
}

function Step() {}

Step.prototype.init = function () {
    return init();
}

Step.prototype.modal = function (mode, message) {
    return stepModal(mode, message);
}

Step.prototype.loader = function(state, message = null) {
    return stepLoading(state, message);
}