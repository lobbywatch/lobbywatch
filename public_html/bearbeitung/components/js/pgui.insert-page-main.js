define(function(require, exports) {

    var controls    = require('pgui.controls'),
        pv          = require('pgui.validation'),
        forms       = require('pgui.forms'),
        // Afterburner
        hints       = require('../templates/custom_templates/js/custom.hints');

    $(function() {
        var $form = $('.pgui-edit-form form');
        $form.pgui_validate_form();

        controls.initEditors($('body'), function(err) {
            var form = new forms.InsertForm($form);
        });

        $form.submit(function(e) {
            $form.find(".btn-toolbar button").prop('disabled', true);
            $form.find(".btn-toolbar button[type=submit],submit").addClass('btn-loading');
            if (!pv.ValidateSimpleForm($form, $form.find('.error-container'), true)) {
                $form.find(".btn-toolbar button").prop('disabled', false);
                $form.find(".btn-toolbar button[type=submit],submit").removeClass('btn-loading');
                e.preventDefault();
            }
        });
    })
});