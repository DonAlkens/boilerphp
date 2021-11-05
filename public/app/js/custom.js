// Class definition
var KTFormRepeater = function() {

    // Private functions
    var demo1 = function() {
        $('#kt_repeater_1').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function () {
                $(this).slideDown();
            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    }

    return {
        // public functions
        init: function() {
            demo1();
        }
    };
}();
jQuery(document).ready(function() {
    KTFormRepeater.init();
});

