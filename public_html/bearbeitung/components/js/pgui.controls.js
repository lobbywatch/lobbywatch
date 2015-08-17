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
                        self.timeEntry({
                            spinnerImage: 'images/spinnerBlue.png',
                            spinnerIncDecOnly: true,
                            showSeconds: true,
                            show24Hours: true
                        });
                        var timeEdit = new editors.TimeEdit(self);
                        timeEdit.updateState();
                        callback();
                    });
                }, callback);
            },

            function(callback) {

                async.forEach(container.find('[data-editor-class=HtmlEditor]').get(), function(item, callback) {
                    var editorContainer = $(item);
                    require(['pgui.wysiwyg'], function(w) {
                        var editor = new w.WYSISYGEditor(editorContainer);
                        var htmlEdit = new editors.HtmlEditor(editorContainer);
                        htmlEdit.updateState();
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

            // DateTime editor
            function(callback) {

                async.forEach(container.find('[data-editor-class=DateTimeEdit]').get(), function(item, callback) {
                    var editor = $(item);
                    require(['pgui.datetimepicker'], function(dtp) {

                        var editorView = new dtp.DateTimePicker(editor, editor.siblings('.btn').first());
                        callback();
                    });
                }, callback)

            },

            // Combobox
            function(callback) {
                async.forEach(container.find('[data-editor-class=ComboBox]').get(), function(item, callback) {
                    var comboBox = new editors.ComboBoxEditor($(item));
                    comboBox.updateState();
                    callback();
                }, callback)

            },

            // Hide invisible editors
            function(callback) {

                async.forEach(container.find('[data-editor=true][data-editor-visible=false]').get(), function(item, callback) {
                    var $editor = $(item);
                    $editor.closest('.control-group').hide();
                    callback();
                }, callback)

            },

            function(callback) { callback(); }
        ],
        callback);

       /* version without async.js
        container.find('[data-editor-class=ComboBox]').each(function(index, item) {
            var comboBox = new editors.ComboBoxEditor($(item));
            comboBox.updateState();
        });
        */

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

        ],callback);


    };

    });