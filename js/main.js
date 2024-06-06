function darkenColor(rgbColor, percent) {
    // Extract RGB values
    var rgbValues = rgbColor.match(/\d+/g);

    // Calculate darker color
    var r = Math.round(rgbValues[0] * (100 - percent) / 100);
    var g = Math.round(rgbValues[1] * (100 - percent) / 100);
    var b = Math.round(rgbValues[2] * (100 - percent) / 100);

    // Construct new RGB color
    var newColor = "rgb(" + r + ", " + g + ", " + b + ")";

    return newColor;
}

function htmlEntityDecode(input) {
    const textarea = document.createElement('textarea');
    textarea.innerHTML = input;
    return textarea.value;
}

$(window).on("load", function() {
    $("body").removeClass("is-loading")
});


(function($) {
    $(document).ready(function() {
        function resetInputs(container) {
            $(container).find('input').each(function() {
                switch (this.type) {
                    case 'text':
                    case 'number':
                        $(this).val('');
                        break;
                    case 'radio':
                    case 'checkbox':

                        $(this).prop('checked', false);
                        $(this).removeAttr('checked');

                        var originalElement = this;
                        var clonedElement = originalElement.cloneNode(true); // true to clone all descendants
                  
                        originalElement.parentNode.replaceChild(clonedElement, originalElement);


                        break;
                    default:
                        $(this).val('');
                }
            });
            
            $(container).find('select').each(function() {
                $(this).prop('selectedIndex', 0);
            });
            
            $(container).find('textarea').each(function() {
                $(this).val('');
            });
        }

        // Example usage: reset all inputs in the container with id 'form-container'
        $('.has-reset-input--trigger').on('click', function() {
            resetInputs($(this).closest('.has-reset-input'));
        });
    });
})(jQuery);
