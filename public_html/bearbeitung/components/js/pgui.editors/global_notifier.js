define(function (require, exports) {
    var Class = require('class');
    var events = require('microevent');

	exports.EditorsGlobalNotifier =  Class.extend({
        valueChanged: function(fieldName)
        {
            this.trigger('onValueChangedEvent', fieldName, 0);
        },

        onValueChanged: function(callback)
        {
            this.bind('onValueChangedEvent', callback);
        }
    });
    
    events.mixin(exports.EditorsGlobalNotifier);
});