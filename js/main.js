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

$(window).on("load", function() {
    $("body").removeClass("is-loading")
});