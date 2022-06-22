function Styler(element) {
    this.htmlElement = element;
    this.htmlCode = $('#box-shadow-code');
    this.borderTopLeftRadius = parseInt($('#border-topLeft').val());
    this.borderTopRightRadius = parseInt($('#border-topRight').val());
    this.borderBottomLeftRadius = parseInt($('#border-bottomLeft').val());
    this.borderBottomRightRadius = parseInt($('#border-bottomRight').val());
	this.borderWidth = parseInt($("#border-width").val());
	this.borderStyle = $("#border-style :selected").val();
    this.borderColor = $("#border-color-alt").val();
}
  
Styler.prototype.borderRefresh = function() {
    var cssCode1 = '', cssCode2 = '';
    cssCode1 += this.borderTopLeftRadius + 'px ' + this.borderTopRightRadius + 'px ' + this.borderBottomRightRadius + 'px ' + this.borderBottomLeftRadius + 'px ';
    cssCode2 += this.borderWidth + 'px ' + this.borderStyle + ' ' + this.borderColor;
	
	this.htmlElement.css('border-radius', cssCode1);
	this.htmlElement.css('border', cssCode2);
	this.htmlElement.css('background-color', this.borderBackground);
};
Styler.prototype.setAllBorderCorners = function (radius) {
    this.borderTopLeftRadius = radius;
    this.borderTopRightRadius = radius;
    this.borderBottomLeftRadius = radius;
    this.borderBottomRightRadius = radius;
};

Styler.prototype.setBorderColor = function(color) {
    this.borderColor = color;
};

Styler.prototype._getFromField = function(value, min, max, elem) {
    var val;

    val = parseFloat(value);
    if (isNaN(val)) {
        val = 0;
    } else if (val < min) {
        val = min;
        value = min;
    } else if (val > max) {
        val = max;
        value = max;
    }
    elem.val(value);

    return val;
};