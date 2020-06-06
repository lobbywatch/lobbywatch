define([
    'pgui.editors/plain',
    'datepicker',
    'locales/datetimepicker_locale',
    'moment'
], function (PlainEditor, datepicker, datetimepicker_locale, moment) {

    function initDatetimePicker($container) {
        var format = $container.data('picker-format');
        var verticalPosition = $container.data('vertical') || 'auto';

        var $wrapper = $container.closest('.js-datetime-editor-wrap');
        if ($wrapper.length == 0) {
            $wrapper = $container
        }

        $wrapper.datetimepicker({
            format: format,
            useCurrent: false,
            showClear: true,
            showClose: true,
            showTodayButton: true,
            widgetPositioning: {vertical: verticalPosition},
            allowInputToggle: false,
            locale: 'php_gen',
            icons: {
                time: 'icon-time',
                date: 'icon-calendar',
                up:   'icon-chevron-up',
                down: 'icon-chevron-down',
                previous: 'icon-chevron-left',
                next:  'icon-chevron-right',
                today: 'icon-today',
                clear: 'icon-remove',
                close: 'icon-ok'
            }
        });

        return $wrapper;
    }

    return PlainEditor.extend({
        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);

            initDatetimePicker(rootElement)
                .on('dp.change', _.bind(this.doChanged, this))
                .on('dp.hide', function () {
                    rootElement.trigger('keyup');
                });
        },

        _enableCalendarButton: function(value) {
            var $calendarButton =  this._getCalendarButton();
            if ($calendarButton.length !== 0) {
                if (!value) {
                    $calendarButton.attr('disabled', true);
                }
                else {
                    $calendarButton.removeAttr('disabled');
                }
            }
        },

        _getCalendarButton: function() {
            return this.rootElement.find('.js-datetime-editor-button');
        },

        setEnabled: function(value) {
            this._super(value);
            this._enableCalendarButton(value);
            return this;
        },

        setReadonly: function(value) {
            this._super(value);
            this._enableCalendarButton(!value);
            return this;
        },

        getValueEx: function() {
            var inputFormat = this.rootElement.data('picker-format');
            return moment(this._super(), inputFormat).format('YYYY-MM-DD HH:mm:ss');
        },

        setValueEx: function(value) {
            var inputFormat = this.rootElement.data('picker-format');
            this._super(moment(value, 'YYYY-MM-DD HH:mm:ss').format(inputFormat));
        }
    });
});
