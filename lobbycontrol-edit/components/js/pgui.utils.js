function IsBrowserVersion(browsers)
{
    var result = true;
    if ((browsers.msie != undefined) && ($.browser.msie))
        result = (browsers.msie != 'none') && (parseInt($.browser.version.slice(0, 1)) >= browsers.msie);
    if ((browsers.opera != undefined) && ($.browser.opera))
        result = (browsers.opera != 'none');
    if ((browsers.webkit != undefined) && ($.browser.webkit))
        result = (browsers.webkit != 'none');
    if ((browsers.mozilla != undefined) && ($.browser.mozilla))
        result = (browsers.mozilla != 'none');
    return result;
}


function WriteWYSIWYGValuesToTheirTextAreas()
{
    var editor = $('.html_wysiwyg');
    editor.each(function(index, element)
    {
        element.value = $(element).val();
    });

}