define(function(require, exports) {

    var controls    = require('pgui.controls'),
        pv          = require('pgui.validation'),
        forms       = require('pgui.forms');

    $(function() {
        var $form = $('.pgui-edit-form form');
        $form.pgui_validate_form();

        controls.initEditors($('body'), function(err) {
            var form = new forms.InsertForm($form);
        });

        $form.submit(function(e) {
            if (!pv.ValidateSimpleForm($form, $form.find('.error-container'), true)) {
                e.preventDefault();
            }
        });
    })
});