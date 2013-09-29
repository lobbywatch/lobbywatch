define(function(require, exports)
{
    require('jquery/jquery.highlight');

    exports.HighlightTextInGrid = function (gridSelector, fieldName, text, opt, a_hint)
    {
        var hint_text = '';
        if (a_hint)
            hint_text = a_hint;


        $(gridSelector + " [data-column-name='"+fieldName+"']").highlight(text, opt, {
            hint: hint_text
        });
        $(gridSelector + " [data-column-name='"+fieldName+"']").highlight(text, opt, {
            hint: hint_text
        });
    };

    exports.HighlightTextInAllGrid = function ($grid, text, opt)
    {        
        $grid.highlight(text, opt);
    };    
});