define(['jquery.highlight'], function() {
    return {
        HighlightTextInGrid: function (gridSelector, fieldName, text, opt, hint) {
            $(gridSelector + " [data-column-name='"+fieldName+"']").highlight(text, opt, {
                hint: hint || ''
            });
        },

        HighlightTextInAllGrid: function ($grid, text, opt) {
            $grid.highlight('' + text, opt);
        }
    };
});