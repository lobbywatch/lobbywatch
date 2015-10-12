define(function(require, exports, module) {

    require('pgui.jquery.utils');
    var _ = require('underscore');
    
    (function($){

        /**
         * ?????
         * @class
         *
         */
        var methods =
        {
            /**
             *
             * @param {object} options [options = {validate_errorClass: , validate_errorPlacement: undefined, validate_success: undefined}] Initialization options
             * @param {string} options.validate_errorClass [options.validate_errorClass = 'help-inline']
             * @param {function} options.validate_errorPlacement [options.validate_errorPlacement = undefined]
             * @param {function} options.validate_success [options.validate_success = undefined]
             * @param {function} options.highlight
             * @param {function} options.unhighlight
             *
             */
            init : function(options)
            {
                var settings = {
                    validate_errorClass:        'help-inline',
                    validate_errorPlacement:    undefined,
                    validate_success: function(label) {
                        $(label).closest('.control-group').removeClass('error');
                        $(label).remove();
                    },
                    highlight: function(label) {
                        var labelControl =  $(label);
                        labelControl.closest('.control-group').addClass('error');
                        var group = labelControl.closest('.control-group').find('.controls');
                        if  ((group.find('.help-inline').length == 0) && !labelControl.parent().is('.controls')) {
                            labelControl.appendTo(group);
                        }

                    },
                    unhighlight: undefined
                };

                if (options)
                    $.extend(settings, options);

                if (this && this.length > 0)
                {
                    require(["jquery/jquery.validate"], _.bind(function()
                    {
                        $.validator.addMethod('regexp', function (value, element, param)
                        {
                            var pattern = new RegExp(param);
                            return value.match(pattern);
                        }, 'Default regexp message');

                        $.validator.addMethod('required_custom', function (value, element, param)
                        {
                            return ($(element).attr('type') === 'file' && $(element).data('has-file')) || value;
                        }, 'Default required message');

                        this.each(function(index, form) {
                            var validationRules = { };
                            var errorMessageMap = { };

                            $(form).find('input,select,textarea').each(function(inputIndex, input) {
                                if ($(input).attr('data-validation') != undefined)
                                {
                                    var rules = $(input).attr('data-validation').split(' ');
                                    var validationRule = { };
                                    var errorMessages = { };

                                    function appendErrorMessage(validatorName, attrName) {
                                        attrName = attrName || validatorName;
                                        if ($(input).hasAttr('data-' + attrName + '-error-message'))
                                            errorMessages[validatorName] =
                                                $(input).attr('data-' + attrName + '-error-message');
                                    }

                                    for(var i = 0; i < rules.length; i++) {
                                        if (rules[i] == 'email')
                                        {
                                            validationRule.email = true;
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'url')
                                        {
                                            validationRule.url = true;
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'required')
                                        {
                                            validationRule.required_custom = true;
                                            appendErrorMessage('required_custom', 'required');
                                        }
                                        else if (rules[i] == 'range')
                                        {
                                            validationRule.range = [
                                                $(input).attr('range-min-value'),
                                                $(input).attr('range-max-value')
                                            ];
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'max-value')
                                        {
                                            validationRule.max = $(input).attr('max-value');
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'min-value')
                                        {
                                            validationRule.min = $(input).attr('min-value');
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'max-length')
                                        {
                                            validationRule.maxlength = $(input).attr('max-length');
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'min-length')
                                        {
                                            validationRule.minlength = $(input).attr('min-length');
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'rangelength')
                                        {
                                            validationRule.rangelength = [
                                                $(input).attr('range-min-length'),
                                                $(input).attr('range-max-length')
                                            ];
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'number')
                                        {
                                            validationRule.number = true;
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'digits')
                                        {
                                            validationRule.digits = true;
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'creditcard')
                                        {
                                            validationRule.creditcard = true;
                                            appendErrorMessage(rules[i]);
                                        }
                                        else if (rules[i] == 'regexp')
                                        {
                                            validationRule.regexp = $(input).attr('regexp');
                                            appendErrorMessage(rules[i]);
                                        }
                                    }
                                    errorMessageMap[$(input).attr('name')] = errorMessages;
                                    validationRules[$(input).attr('name')] = validationRule;
                                }

                            });

                            $(form).validate({
                                errorClass:     settings.validate_errorClass,
                                errorPlacement: settings.validate_errorPlacement,
                                errorElement: 'span',
                                ignore: '',
                                success:        settings.validate_success,
                                rules:          validationRules,
                                messages:       errorMessageMap,
                                highlight: settings.highlight,
                                unhighlight: settings.unhighlight
                            });
                        });

                    }, this));
                }

            }
        };
        /**
         * See (http://jquery.com)
         * @name jQuery
         * @class
         * See the jQuery Library (http://jquery.com) for full details
         */
        /**
         * See (http://jquery.com)
         * @name fn
         * @class
         * See the jQuery Library (http://jquery.com) for full details
         * @memberOf jQuery
         */
        /**
         * Validation plugin
         * @name pgui_validate_form
         * @class
         * @return {*}
         * @memberOf jQuery.fn
         */
        $.fn.pgui_validate_form = function( method )
        {

            if (methods[method])
            {
                return methods[method].apply(this,Array.prototype.slice.call(arguments, 1));
            }
            else if (typeof method === 'object' || !method)
            {
                return methods.init.apply(this, arguments);
            }
            else
            {
                $.error('Method ' +  method + ' does not exist on jQuery.pgui_effects');
            }
        };

    })(jQuery);

    function ErrorInfo(message)
    {
        var _message = message;

        this.GetMessage = function() {
            return _message;
        };

        this.SetMessage = function(value) {
            _message = value;
        }
    }

    /**
     * @returns {Object} FormValidationResult
     * @returns {boolean} FormValidationResult.valid
     * @returns {string} FormValidationResult.message
     * @type {Grid_ValidateForm}
     */
    var Grid_ValidateForm = exports.Grid_ValidateForm = function (form, isInsert)
    {
        var values = [];
        var errorInfo = new ErrorInfo();
        form.find('*[data-pgui-legacy-validate]').each(function(index, element)
        {
            values[$(element).attr('data-legacy-field-name')] = $(element).val();
        });

        var result;
        if (isInsert)
            result = InsertValidation(values, errorInfo);
        else
            result = EditValidation(values, errorInfo);

        if (result)
            return { valid: true, message: '' };
        else
            return { valid: false, message: errorInfo.GetMessage() };
    };

    function CreateErrorMessage(message) {
        return $('<div class="alert alert-error"><button class="close" data-dismiss="alert"><i class="icon-remove"></i></button>' +
            message + '</div>');
    }

    exports.ValidateSimpleForm = function (form, errorContainer, isInsert)
    {
        if (!form.valid())
            return false;

        var legacyValidateForm = Grid_ValidateForm(form, isInsert);
        if (!legacyValidateForm.valid) {
            errorContainer.append(CreateErrorMessage(legacyValidateForm.message));
            return false;
        }
        else {
            errorContainer.find('*').remove();
            //HideLegacyErrors(errorContainer);
        }
        return true;
    }

});
