// Highlight plugin

(function($)
{
    $.fn.highlight = function()
    {
        var _this = this;
        
        function stepOne()
        {
            _this.animate({ opacity : 0 }, { complete : stepTwo, duration : 'fast' });
        }

        function stepTwo()
        {
            _this.animate({ opacity : 1 }, { duration : 'fast' });
        }
        
        // -----
        
        stepOne();
    };
    
})(jQuery);