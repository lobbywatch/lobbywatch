define([
    'class',
    'microevent',
    'underscore'
], function (Class, events, _) {

    return events.mixin(Class.extend({
        /**
         * @param rootElement jQuery
         */
        init: function(rootElement, readyCallback) {
            this.rootElement = rootElement;
            this.fieldName = this.rootElement.attr('data-field-name');

            var isVisible = rootElement.data('editor-visible');
            if (!_.isUndefined(isVisible)) {
                this.setVisible(isVisible);
            }

            if (_.isFunction(readyCallback)) {
                readyCallback(this);
            }
        },

        doChanged: function() {
            this.trigger('onChangeEvent', this, 0);
        },

        getValue: function() {
            return null;
        },

        getValueEx: function() {
            return this.getValue();
        },

        setValue: function(value) {
            return this;
        },

        setValueEx: function(value) {
            this.setValue(value);
        },

        getDisplayValue: function () {
            return this.getValue();
        },

        getData: function() {
            return this.getValue();
        },

        setData: function(data) {
            this.setValue(data);
            return this;
        },

        onChange: function(callback) {
            this.bind('onChangeEvent', callback);
            return this;
        },

        getRootElement: function() {
            return this.rootElement;
        },

        getFieldName: function() {
            return this.fieldName;
        },

        isReadOnly: function() {
            return this.readonly();
        },

        getVisible: function() {
            return this.rootElement.closest('.form-group').is(':visible');
        },

        setVisible: function(value) {
            this.rootElement.closest('.form-group').toggle(value).prev('.form-group-label').toggle(value);
            this._getLabelGroup().toggle(value);
            return this;
        },

        visible: function(value) {
            var controlContainer = this.rootElement.closest('.form-group');
            if (controlContainer.length === 0) {
                return;
            }
            if (_.isUndefined(value)) {
                return this.getVisible();
            }

            this.setVisible(value);
        },

        enabled: function(value) {
            if (_.isUndefined(value)) {
                return this.getEnabled();
            }

            if (this.getEnabled() != value) {
                this.setEnabled(value);
            }
        },

        getEnabled: function() {
            return true;
        },

        setEnabled: function(value) {
            return this;
        },

        readonly: function(value) {
            if (_.isUndefined(value))
            {
                return this.getReadonly();
            }
            else
            {
                if (this.getReadonly() != value)
                {
                    this.setReadonly(value);
                }
            }
        },

        getReadonly: function() {
            return false;
        },

        setReadonly: function(value) {
            return this;
        },

        setValidationErrorMessage: function(validatorName, message) {
            var validation = this.rootElement.closest('form').data('validator');
            if (!validation) {
                return this;
            }

            var name = this.rootElement
                .closest('.col-input')
                .find('input,select,textarea')
                .first()
                .attr('name');

            if (name && validation.settings.messages[name]) {
                validation.settings.messages[name][validatorName] = message;

                if (validatorName === 'required') {
                    validation.settings.messages[name]['required_custom'] = message;
                }
            }

            return this;
        },

        getState: function () {
            var $formGroup = this.rootElement.closest('.form-group');
            var states = ['warning', 'error', 'success'];
            for (var i in states) {
                if ($formGroup.hasClass('has-' + states[i])) {
                    return states[i];
                }
            }

            return 'normal';
        },

        setState: function (state) {
            if (['normal', 'warning', 'error', 'info', 'success'].indexOf(state) === -1) {
                return;
            }

            var classes = 'has-warning has-error has-success';
            var $labelGroup = this._getLabelGroup().removeClass(classes);
            var $inputGroup = this.rootElement.closest('.form-group').removeClass(classes);

            if (state === 'normal') {
                return this;
            }

            $labelGroup.addClass('has-' + state);
            $inputGroup.addClass('has-' + state);

            return this;
        },

        setHint: function (hint) {
            var $formGroup = this.rootElement.closest('.form-group');
            var $helpBlock = $formGroup.find('.help-block');

            if (!hint) {
                $helpBlock.remove();
                return this;
            }

            if ($helpBlock.length === 0) {
                $formGroup.append($('<p>').addClass('help-block').html(hint));
            } else {
                $helpBlock.html(hint);
            }

            return this;
        },

        isMultivalue: function () {
            return false;
        },

        destroy: function () {
        },

        setRequired: function (isRequired) {
            this._getLabelGroup().find('span.required-mark').toggle(Boolean(isRequired));
            var command = isRequired ? 'add' : 'remove';
            var $target = this._getSetRequiredTarget();
            $target.rules(command, 'required_custom');
            $target.rules(command, 'required');
            return this;
        },

        getRequired: function () {
            return this._getLabelGroup().find('span.required-mark').is(':visible');
        },

        _getSetRequiredTarget: function () {
            return this.getRootElement();
        },

        _getLabelGroup: function () {
            return $('.control-label[for=' + this._getId() + ']').closest('.form-group');
        },

        _getId: function () {
            return this.rootElement.attr('id');
        }
    }));

});
