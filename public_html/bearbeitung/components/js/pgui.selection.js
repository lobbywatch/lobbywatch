define(['class', 'libs/store', 'microevent'], function (Class, store, events) {

    function removeFromData(data, value) {
        return _.filter(data, function (v) {
            return v.join('_') !== value.join('_');
        });
    }

    return events.mixin(Class.extend({
        init: function (prefix) {
            this.prefix = prefix + '_selection';
        },

        getData: function () {
            return store.get(this.prefix) || [];
        },

        add: function (value) {
            var data = removeFromData(this.getData(), value);
            data.push(value);

            store.set(this.prefix, data);
            this.trigger('change', data);

            return this;
        },

        remove: function (value) {
            var data = this.getData();
            var nextData = removeFromData(data, value);
            store.set(this.prefix, nextData);
            this.trigger('change', nextData);

            return this;
        },

        toggle: function (value, isEnabled) {
            if (isEnabled) {
                this.add(value);
            } else {
                this.remove(value);
            }

            return this;
        },

        clear: function () {
            store.remove(this.prefix);
            this.trigger('change', []);
        }
    }));

});