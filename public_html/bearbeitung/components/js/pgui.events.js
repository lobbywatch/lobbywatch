define(['class', 'microevent'], function(Class, events) {

    var InputEvents = Class.extend({
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

    return {
        InputEvents: events.mixin(InputEvents),
        setupInputEvents: function($input) {
           $input.data('pg-events', new InputEvents($input));
        }
    };

});