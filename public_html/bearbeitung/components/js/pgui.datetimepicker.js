define(function(require, exports) {

    var Class   = require('class'),
        events  = require('microevent'),
        _       = require('underscore');

    var DateTimePicker = exports.DateTimePicker = Class.extend({

        init: function($container, $triggerButton) {
            var self = this;
            this.$container  = $container;
            this.$triggerButton = $triggerButton || $container;
            this.$container.data('DateTimePicker-class', self);

            this.dateFormat = this.$container.attr('data-picker-format');
            this.showTime = this.$container.attr('data-picker-show-time') == 'true';
            this.fdow = this.$container.attr('data-picker-first-day-of-week');

            self.visible = false;
            this.calendar = null;
            require(["libs/calendar/js/jscal2.js"], function() {
                self._initCalendar();
                self.$triggerButton.click(function() {

                    if (self.visible)
                        self.calendar.hide();
                    else {
                        var av = /(^|[^%])%[bBmo]/.exec(self.calendar.args.dateFormat);
                        var au = /(^|[^%])%[de]/.exec(self.calendar.args.dateFormat);
                        if (av && au) {
                            var at = av.index < au.index;
                        }
                        var aw = Calendar.parseDate(self.$container.val(), at);
                        if (aw) {
                            self.calendar.selection.set(aw, false, true);
                            self.calendar.moveTo(aw);
                        }
                        self.calendar.els.topCont.style.visibility = "";
                        self.calendar.els.topCont.style.display = "";
                        document.body.appendChild(self.calendar.els.topCont);

                        var fixedCont = (self.$container.closest('.modal').length > 0);

                        self.calendar.showAt(
                            self.$container.offset().left,
                            self.$container.offset().top + self.$container.outerHeight() - (fixedCont ? $(window).scrollTop() : 0),
                            true,
                            fixedCont ? "fixed" : "absolute"); //'Bl/Bl/Bl/Bl/Bl'
                        self.calendar.focus();
                    }
                    self.visible = !self.visible;
                });

            });
        },

        onChange: function(callback) {
            this.bind('change', callback);
        },

        _initCalendar: function() {
            var self = this;
            var options = {
                showTime       : self.showTime,
                inputField     : this.$container.get(),
                minuteStep     : 1,
                onSelect       : function() {
                    var dateFormat = (this.args.dateFormat == 0) ? self.dateFormat : this.args.dateFormat;
                    this.hide();
                    self.visible = false;
                    self.$container.val(this.selection.print(this.args.dateFormat));
                    self.$container.focus();
                    self.$container.keyup();
                    self.trigger('change');
                }
            };
            if (this.dateFormat)
                options = _.extend(options, { dateFormat: this.dateFormat });
            if (this.fdow)
                options = _.extend(options, { fdow: this.fdow-0 });

            this.calendar = Calendar.setup(options);

            this.calendar.addEventListener("onBlur", function() {
                self.$container.focus();
            });
        }
    });
    events.mixin(DateTimePicker);

    exports.setupCalendarControls = function(container) {
        container.find('[data-calendar=true]').each(function() {
            new DateTimePicker($(this));
        });
    };

});