define(function(require, exports) {

    var Class   = require('class'),
        events  = require('microevent');


    var InputEvents = exports.InputEvents = Class.extend({
        init: function($input) {
            this.$input = $input;
        },

        onChange: function(callback) {
            this.bind('changed', callback);
        },

        changed: function() {
            this.trigger('changed');
        }
    });
    events.mixin(InputEvents);

    var setupInputEvents = exports.setupInputEvents = function($input) {
        $input.data('pg-events', new InputEvents($input));
    };


});