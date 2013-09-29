define(function(require, exports) {

    var async = require('async'),
        editors = require('pgui.editors');

    exports.initEditors = function(container, callback) {

        callback = callback || function() {};

        async.parallel([

            function(callback) {
                async.forEach(container.find('[spinedit=true]').get(), function(item, callback) {
                    var self = $(item);
                    require(['jquery.spinbox'], function() {
                        self.spinbox({
                            buttonWidth: 10,
                            mousewheel: true,
                            min: self.attr('min-value'),
                            max: self.attr('max-value')
                        });
                        callback();
                    });
                }, callback); 
            },

            function(callback) {
                async.forEach(container.find('input[timeedit=true]').get(), function(item, callback) {
                    var self = $(item);
                    require(['jquery.timeentry'], function() {
                        var timeEditInput = self;
                        timeEditInput.wrap($('<span>').addClass('time-edit-wrapper'));
                        timeEditInput.addClass("time-edit");
                        timeEditInput.timeEntry({
                            spinnerImage: 'images/win7_spinners.png',
                            spinnerSize: [17, 20, 0],
                            spinnerIncDecOnly: true,
                            showSeconds: true,
                            show24Hours: true
                        });
                        callback();
                    });
                }, callback);
            },

            function(callback) {

                async.forEach(container.find('[data-editor-class=HtmlEditor]').get(), function(item, callback) {
                    var editorContainer = $(item);
                    require(['pgui.wysiwyg'], function(w) {
                        var editor = new w.WYSISYGEditor(editorContainer);
                        callback();
                    });
                }, callback);
            },

            function(callback) {
                async.forEach(container.find('input[masked=true]').get(), function(item, callback) {
                    var maskEdit = $(item);
                    require(["jquery/jquery.maskedinput"], function()
                    {
                        if (maskEdit.attr('mask') != '')
                            maskEdit.mask(maskEdit.attr('mask'));
                        callback();
                    });
                }, callback);
            },

            function(callback) {

                async.forEach(container.find('[autocomplete=true]').get(), function(item, callback) {
                    var input = $(item);

                    require(['libs/ajax-chosen'], function() {
                        input.ajaxChosen({
                            method: 'GET',
                            url: input.attr('data-url'),
                            dataType: 'json',
                            minTermLength: 0
                        }, function (data) {

                            var terms = {};
                            $.each(data, function (i, val) {
                                terms[val.id] = val.value;
                            });
                            return terms;
                        });
                        callback();
                    });
                }, callback);
            },


            function(callback) {

                async.forEach(container.find('div.btn-group[data-toggle-name]').get(), function(item, callback) {
                    var group   = $(item);
                    var form    = group.parents('form').eq(0);
                    var name    = group.attr('data-toggle-name');
                    var hidden  = $('input[name="' + name + '"]', form);

                    $('button', group).each(function(){
                        var button = $(this);
                        button.live('click', function(){
                            hidden.val($(this).val());
                        });
                        if(button.val() == hidden.val()) {
                            button.addClass('active');
                        }
                    });
                    callback();
                }, callback);
            },


            function(callback) {

                async.forEach(container.find('.field-options').get(), function(item, callback) {
                    var optionsPanel = $(item);

                    optionsPanel.find('.set-default.btn').mouseup(function() {
                        var button = $(this);
                        setTimeout(function() {
                            optionsPanel.find('.set-default-input').val(button.hasClass('active') ? '1' : '0');
                        }, 0);
                    });

                    optionsPanel.find('.set-to-null.btn').mouseup(function() {
                        var button = $(this);
                        setTimeout(function() {
                            optionsPanel.find('.set-to-null-input').val(button.hasClass('active') ? '1' : '0');
                        }, 0);
                    });

                    callback();
                }, callback);
            },


            function(callback) {

                async.forEach(container.find('[multi-autocomplete=true]').get(), function(item, callback) {
                    var input = $(item);

                    require(['libs/ajax-chosen'], function() {

                        var parentAutoComplete = $('[name="' + input.attr('parent-autocomplete') + '"]');

                        parentAutoComplete.chosen().change(function() {
                            input.find('option:not(:first)').remove();
                            input.find('option:first').val('');
                            input.find('option:first').html('');
                            input.trigger("liszt:updated");
                            input.data('chosen').results_reset();
                            input
                                .next('.chzn-container')
                                .find('.chzn-single').data('prevVal', Math.random().toString());
                        });

                        if (input.attr('data-multileveledit-main') == 'true')
                            input.chosen().change(function() {
                                editors.multiLevelAutoCompleteGlobalNotifier.valueChanged(input.closest('table').attr('data-field-name'));
                            });


                        input.ajaxChosen({
                            method: 'GET',
                            url: function() {
                                if (input.attr('parent-autocomplete')) {
                                    var parentAutoComplete = $('[name="' + input.attr('parent-autocomplete') + '"]');
                                    var parentValue = parentAutoComplete.val();
                                    return input.attr('data-url')
                                        + '&term2=' + parentValue;
                                }
                                else {
                                    return input.attr('data-url');
                                }
                            },
                            dataType: 'json',
                            minTermLength: 0
                        }, function (data) {
                            var terms = {};
                            $.each(data, function (i, val) {
                                terms[val.id] = val.value;
                            });

                            return terms;
                        });
                    });
                    callback();
                }, callback);


            },

            function(callback) {

                async.forEach(container.find('[data-editor-class=DateTimeEdit]').get(), function(item, callback) {

                    var editor = $(item);

                    require(['pgui.datetimepicker'], function(dtp) {

                        var editorView = new dtp.DateTimePicker(editor, editor.siblings('.btn').first());
                        callback();
                    });

                }, callback)

            },

            function(callback) { callback(); }
        ],
        callback);

    };


    exports.destroyEditors = function(container, callback) {

        callback = callback || function() {};



        async.parallel([

            function(callback) {
                async.forEach(container.find('[data-editor-class=HtmlEditor]').get(), function(item, callback) {
                    var editorContainer = $(item);
                    require(['pgui.wysiwyg'], function(w) {
                        if (editorContainer.data('pgui-class'))
                            editorContainer.data('pgui-class').destroy();
                        callback();
                    });
                }, callback);
            }

        ]);


    };

    });