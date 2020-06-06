define([
    'pgui.editors/plain',
    'signature_pad'
], function (PlainEditor, Signature) {

    return PlainEditor.extend({

        init: function (rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            var self = this;
            this._history = [];
            var initialValue = '';
            var $signatureContainer = rootElement.closest('div');
            var canvas = $signatureContainer.find('canvas').first().get(0);
            this.formatForSaving = rootElement.data('format-for-saving');
            this.$clearBtn = $signatureContainer.find("[data-action=clear]");
            this.$undoBtn = $signatureContainer.find("[data-action=undo]");
            var penColor = rootElement.data('pen-color') ? rootElement.data('pen-color') : 'black';
            var backgroundColor = rootElement.data('background-color') ? rootElement.data('background-color') : 'transparent';
            this.signaturePad = new Signature(canvas, {
                penColor: penColor,
                backgroundColor: backgroundColor,
                onEnd: function() {
                    self.rootElement.val(self.toDataURL());
                    self._history.push(self.getValue());
                    self.$undoBtn.prop('disabled', false);
                }
            });
            if (this.getValue()) {
                this.signaturePad.fromDataURL(this.getValue());
                initialValue = this.getValue();
            }

            this._enableControls(this.getEnabled() && !this.getReadonly());

            this.$clearBtn.click(function() {
                self.signaturePad.clear();
                self.rootElement.val('');
                self._history = [];
                initialValue = '';
                self.$undoBtn.prop('disabled', true);
                return false;
            });

            this.$undoBtn.click(function() {
                self._history.pop();
                if (self._history.length == 0) {
                    self.setValue(initialValue);
                    $(this).prop('disabled', true);
                } else {
                    self.setValue(self._history[self._history.length - 1]);
                }
                return false;
            });

        },

        setValue: function(value) {
            this.rootElement.val(value);
            this.signaturePad.clear();
            this.signaturePad.fromDataURL(value);

            return this;
        },

        toDataURL: function() {
            if (this.formatForSaving == 'svg') {
                return this.signaturePad.toDataURL("image/svg+xml");
            } else if (this.formatForSaving == 'jpg') {
                return this.signaturePad.toDataURL("image/jpeg")
            } else {
                return this.signaturePad.toDataURL();
            }
        },

        setEnabled: function(value) {
            this._super(value);
            this._enableControls(value && !this.getReadonly());
            return this;
        },

        setReadonly: function(value) {
            this._super(value);
            this._enableControls(!value && this.getEnabled());
            return this;
        },

        _enableControls: function(value) {
            if (value) {
                this.signaturePad.on();
                this.$clearBtn.prop('disabled', false);
                if (this._history.length > 0) {
                    this.$undoBtn.prop('disabled', false);
                }
            } else {
                this.signaturePad.off();
                this.$clearBtn.prop('disabled', true);
                this.$undoBtn.prop('disabled', true);
            }
        }

    });

});
