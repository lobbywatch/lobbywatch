define([
    'pgui.editors/custom',
    'pgui.editors/combobox',
    'jquery.query'
], function (CustomEditor, Combobox) {

    return CustomEditor.extend({

        init: function(rootElement, readyCallback) {
            this._super(rootElement, readyCallback);
            this.levels = [];
            this._processElement(rootElement);
            this.bind('submit.pgui.nested-insert', function ($insertButton, primaryKey, record) {
                var $level = $insertButton.closest('.input-group').find('select');
                for (var i = 0; i < this.levels.length; i++) {
                    if (this.levels[i].rootElement.attr('data-id') == $level.attr('data-id')) {
                        this._addLevelValue(this.levels[i], $insertButton, primaryKey, record[$insertButton.data('display-field-name')]);
                        if (i == this.levels.length - 1) {
                            this.doChanged();
                        }
                    }
                }
            }.bind(this));
        },

        _processElement: function (rootElement) {
            var $rootElement = $(rootElement);
            var self = this;

            var $levels = $rootElement.find("select");
            $levels.each(function (index, item) {
                var $item = $(item);
                self.levels.push(new Combobox($item));
            });

            for (var i = 0; i < self.levels.length; i++) {
                (function(){
                    var level = self.levels[i];
                    var parentLevel = (i - 1 < 0) ? null : self.levels[i - 1];
                    if (parentLevel) {
                        var $insertButton = level.rootElement.closest('td').find('.js-nested-insert');
                        self.updateNestedInsertLink(parentLevel, level, $insertButton);
                        level.setEnabled(parentLevel.getValue() && parentLevel.getEnabled());
                        $insertButton.prop('disabled', !level.getEnabled());

                        parentLevel.onChange(function () {
                            var enabled = this.getValue() && this.getEnabled();
                            level.setEnabled(false);
                            $insertButton.prop('disabled', true);
                            level.clear();
                            level.doChanged();
                            if (enabled) {
                                $.ajax({
                                    url: level.rootElement.data("url"),
                                    data: {term2: parentLevel.getValue()},
                                    dataType: "json"
                                }).success(function (data) {
                                    $.each(data, function (k, item) {
                                        level.addItem(
                                            item.id,
                                            item.value
                                        );
                                    });
                                    level.setEnabled(true);
                                    $insertButton.prop('disabled', false);
                                    self.updateNestedInsertLink(parentLevel, level, $insertButton);
                                });
                            }
                        });
                    }
                })();
            }

            $levels.eq($levels.length - 1).on("change", function () {
                self.doChanged();
            });

        },

        _addLevelValue: function(level, $insertButton, value, displayValue) {
            level.addItem(
                value,
                displayValue
            );
            level.setValue(value);
            level.doChanged();
        },

        updateNestedInsertLink: function(parentLevel, level, $insertButton) {
            if (parentLevel.getValue() && ($insertButton.length > 0)) {
                var url = jQuery.query.load($insertButton.data('content-link'))
                    .set('parent-field-name', level.rootElement.data('parent-link-field-name'))
                    .set('parent-field-value', parentLevel.getValue())
                    .toString();
                $insertButton.data('content-link', url);
            }
        },

        _getMainControl: function() {
            return this.rootElement.find("[data-editor-main]");
        },

        getValue: function() {
            return this._getMainControl().val();
        }

    });

});
