define([
    'underscore',
    'jquery.validate'
], function(_) {

    function validateTabs(element) {
        var $formTabsContainer = $(element).closest('form').find('.form-tabs-container');
        if ($formTabsContainer.length > 0) {
            $formTabsContainer.find('a span.required').remove();
            var $tabContent = $(element).closest('.tab-content');
            if ($tabContent.length > 0) {
                $tabContent.find('.tab-pane:has(.form-group.has-error)').each(function() {
                    var id = $(this).attr('id');
                    var $tab = $formTabsContainer.find('a[href^="#' + id + '"]');
                    $tab.append(' <span class="required">***</span>');
                });
            }
        }
    }

    var methods = {

        init: function(options) {
            var settings = {
                validate_errorClass: 'help-block',
                validate_errorPlacement: function (helpBlock, element) {
                    var target = element.closest('.col-input,.form-group');
                    if (target.length === 0) {
                        target = element.parent();
                    }

                    helpBlock.appendTo(target);
                },
                validate_success: function(element) {
                    var $formGroup = $(element).closest('.form-group').removeClass('has-error');
                    $formGroup.prev('.form-group-label').removeClass('has-error');
                    validateTabs(element);
                    $(element).remove();
                },
                highlight: function(element) {
                    var $formGroup = $(element).closest('.form-group').addClass('has-error');
                    $formGroup.prev('.form-group-label').addClass('has-error');
                    validateTabs(element);
                },
                unhighlight: undefined
            };

            if (options) {
                $.extend(settings, options);
            }

            if (this && this.length > 0) {
                $.validator.addMethod('regexp', function (value, element, param) {
                    var pattern = new RegExp(param);
                    return value.match(pattern);
                }, 'Default regexp message');

                $.validator.addMethod('required_custom', function (value, element, param) {
                    return ($(element).attr('type') === 'file' && $(element).data('has-file')) || value;
                }, 'Default required message');

                this.each(function(index, form) {
                    var validationRules = { };
                    var errorMessageMap = { };

                    $(form).find('input,select,textarea').each(function(inputIndex, input) {
                        if ($(input).attr('data-validation') != undefined) {
                            var rules = $(input).attr('data-validation').split(' ');
                            var validationRule = { };
                            var errorMessages = { };

                            function appendErrorMessage(validatorName, attrName) {
                                attrName = attrName || validatorName;
                                var data = $(input).data();
                                if (data[attrName + 'ErrorMessage']) {
                                    errorMessages[validatorName] = data[attrName + 'ErrorMessage'];
                                }
                            }

                            for(var i = 0; i < rules.length; i++) {
                                if (rules[i] == 'email') {
                                    validationRule.email = true;
                                    appendErrorMessage(rules[i]);
                                } else if (rules[i] == 'url') {
                                    validationRule.url = true;
                                    appendErrorMessage(rules[i]);
                                } else if (rules[i] == 'required') {
                                    validationRule.required_custom = true;
                                    appendErrorMessage('required_custom', 'required');
                                } else if (rules[i] == 'range') {
                                    validationRule.range = [
                                        $(input).attr('range-min-value'),
                                        $(input).attr('range-max-value')
                                    ];
                                    appendErrorMessage(rules[i]);
                                } else if (rules[i] == 'max-value') {
                                    validationRule.max = Number($(input).attr('max-value'));
                                    appendErrorMessage('max', 'maxValue');
                                } else if (rules[i] == 'min-value') {
                                    validationRule.min = Number($(input).attr('min-value'));
                                    appendErrorMessage('min', 'minValue');
                                } else if (rules[i] == 'max-length') {
                                    validationRule.maxlength = $(input).attr('max-length');
                                    appendErrorMessage('maxlength', 'maxLength');
                                } else if (rules[i] == 'min-length') {
                                    validationRule.minlength = $(input).attr('min-length');
                                    appendErrorMessage('minlength', 'minLength');
                                } else if (rules[i] == 'rangelength') {
                                    validationRule.rangelength = [
                                        $(input).attr('range-min-length'),
                                        $(input).attr('range-max-length')
                                    ];
                                    appendErrorMessage(rules[i]);
                                } else if (rules[i] == 'number') {
                                    validationRule.number = true;
                                    appendErrorMessage(rules[i]);
                                } else if (rules[i] == 'digits') {
                                    validationRule.digits = true;
                                    appendErrorMessage(rules[i]);
                                } else if (rules[i] == 'creditcard') {
                                    validationRule.creditcard = true;
                                    appendErrorMessage(rules[i]);
                                } else if (rules[i] == 'regexp') {
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
            }

        }
    };

    $.fn.pgui_validate_form = function(method) {
        if (methods[method]) {
            return methods[method].apply(this,Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' +  method + ' does not exist on jQuery.pgui_effects');
        }
    }

    function ErrorInfo(message) {
        var _message = message;

        this.GetMessage = function() {
            return _message;
        };

        this.SetMessage = function(value) {
            _message = value;
        }
    }

    return {
        validateForm: function ($form) {
            if (!$form.valid()) {
                return {success: false, message: null};
            }

            var values = {};
            var errorInfo = new ErrorInfo();
            $form.find('*[data-pgui-legacy-validate]').each(function(index, element) {
                var $el = $(element);

                if ($el.data('editor') && $el.data('editor').getValue) {
                    values[$el.data('legacy-field-name')] = $el.data('editor').getValue();
                }
            });

            var validationCallback = window[$form.attr('id') + 'Validation']
            var result = _.isFunction(validationCallback)
                ? validationCallback(values, errorInfo)
                : true;

            return {success: result, message: errorInfo.GetMessage()};
        }
    };
});
