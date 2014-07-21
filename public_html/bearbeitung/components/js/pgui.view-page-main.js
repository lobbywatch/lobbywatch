define(function(require, exports, module) {
    $(function() {
        var $body = $('body');
        require(['pgui.layout'], function(instance){
            instance.updatePopupHints($body);
        });
    });
});
