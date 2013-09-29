define(function(require, exports) {

    $.fn.hasAttr = function(name)
    {
        var attr = this.attr(name);
        return this.is("[" + name + "]") && (attr !== undefined) && (typeof attr !== 'undefined') && (attr !== false);
    };

    $.fn.attrDef = function(name, defaultValue)
    {
        return this.hasAttr(name) ? this.attr(name) : defaultValue;
    };

});