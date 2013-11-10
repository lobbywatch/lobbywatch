define(function(require, exports) {

    var Class = require('class'),
        editors = require('pgui.editors');

    var Form = exports.Form = Class.extend({
        init: function($container) {
            this.$container = $container;
        }
    });

    exports.EditForm = Form.extend({
        init: function($container) {
            this._super($container);
            editors.InitEditorsController(editors.DataOperation.Edit, this.$container);
        }
    });

    exports.InsertForm = Form.extend({
        init: function($container) {
            this._super($container);
            editors.InitEditorsController(editors.DataOperation.Insert, this.$container);
        }
    });

});