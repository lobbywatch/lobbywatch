/*

highlight v3

Highlights arbitrary terms.

<http://johannburkard.de/blog/programming/javascript/highlight-javascript-text-higlighting-jquery-plugin.html>

MIT license.

Johann Burkard
<http://johannburkard.de>
<mailto:jb@eaio.com>

*/

jQuery.fn.highlight = function(pat, opt, options)
{
    var defaults =
    {
        hint: ''
    };

    var options = $.extend(defaults, options);
    
    function innerHighlight(node, pat, opt, options)
    {
        var skip = 0;
        if (node.nodeType == 3)
        {
            var pos = -1;
            if (opt == 'END')
                pos = node.data.toUpperCase().lastIndexOf(pat);
            else
                pos = node.data.toUpperCase().indexOf(pat);
            var dataLength = node.data.toUpperCase().length;
            if (
                ((pos >= 0) && (opt == 'ALL')) ||
                ((pos == 0) && (opt == 'START')) ||
                ((pos >= 0) && (pos >= (dataLength - pat.length)) && (opt == 'END'))
               )
            {
                
                var spannode = document.createElement('span');
                spannode.title = options.hint;
                spannode.className = 'highlight';
                var middlebit = node.splitText(pos);
                var endbit = middlebit.splitText(pat.length);
                var middleclone = middlebit.cloneNode(true);
                spannode.appendChild(middleclone);
                middlebit.parentNode.replaceChild(spannode, middlebit);
                skip = 1;
            }
        }
        else if (node.nodeType == 1 && node.childNodes && !/(script|style)/i.test(node.tagName))
        {
            for (var i = 0; i < node.childNodes.length; ++i)
            {
                i += innerHighlight(node.childNodes[i], pat, opt, options);
            }
        }
        return skip;
    }
    
    return this.each(
        function()
        {
            if (pat != '')
                innerHighlight(this, pat.toUpperCase(), opt, options);
        }
    );
};

jQuery.fn.removeHighlight = function()
{
    return this.find("span.highlight").each
    (
        function()
        {
            this.parentNode.firstChild.nodeName;
            with (this.parentNode)
            {
                replaceChild(this.firstChild, this);
                normalize();
            }
        }
    ).end();
};
