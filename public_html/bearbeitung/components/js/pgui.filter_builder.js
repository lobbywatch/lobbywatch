define([
    'class',
    'underscore',
    'pgui.filter_common',
    'pgui.events',
    'pgui.editors',
    'pgui.localizer',
    'pgui.shortcuts'
], function (Class, _, Filter, pgevents, editors, localizer, shortcuts) {
    var Builder = Class.extend({
        init: function ($modal) {
            this.$modal = $modal;
            this.$container = $modal.find('.js-filter-builder-container');
            this.$disableFilterCheckbox = $modal.find('.js-filter-builder-disable').first();
            this.columns = [];
            this.filterComponent = new Group();

            var self = this;

            this.$modal
                .on('shown.bs.modal', function() {
                    shortcuts.push(['filter_builder']);
                })
                .on('click', '.js-filter-builder-commit', function(e) {
                    e.preventDefault();
                    self.filterComponent = self.currentFilterComponent;
                    self.submit();
                })
                .on('hidden.bs.modal', function(e) {
                    self.hide();
                    shortcuts.pop();
                })
            ;

            this.$modal
                .on('submit', function (e) {
                    e.preventDefault();
                })
                .on('click', '.js-group-add-component', function (e) {
                    var type = $(e.currentTarget).data('type');
                    var $group = getComponentElement(e.currentTarget);
                    var condition = new Condition(self._getDefaultColumn());
                    var component = type === 'condition'
                        ? condition
                        : new Group(null, [condition]);

                    $group.data('component').addChild(component);
                    self._addComponentToDOM($group.find('.js-group-content').first(), component, true)
                    return false;
                })
                .on('click', '.js-component-remove', function(e) {
                    var $component = getComponentElement(e.currentTarget);
                    var component = $component.data('component');

                    if (self.currentFilterComponent === component) {
                        self.currentFilterComponent = new Group();
                        self.$disableFilterCheckbox.prop('checked', false);
                        var $componentDOM  = self.currentFilterComponent.getDOM();
                        $component.replaceWith($componentDOM, self.columns);
                        $componentDOM.find('td').first().removeAttr('colspan');
                    } else {
                        self.currentFilterComponent.removeChild(component);
                        $component.remove();
                    }

                    return false;
                })
                .on('click', '.js-group-operator-select', function(e) {
                    var $link = $(e.currentTarget);
                    var $component = getComponentElement(e.currentTarget);
                    var data = $link.data();
                    $component.find('.js-group-operator-text').first().text(data.translate);
                    $component.data('component').setOperator(data.operator);
                    $link.blur().closest('.dropdown').find('.dropdown-toggle').dropdown('toggle');
                    return false;
                })
                .on('change', '.js-condition-field-select', function(e) {
                    var $option = $(e.currentTarget);
                    var $component = getComponentElement(e.currentTarget);
                    var component = $component.data('component');
                    component.setColumn(self._getColumnByName($option.val()));
                    $component.replaceWith(component.getDOM(self.columns));
                })
                .on('change', '.js-condition-operator-select', function(e) {
                    var $option = $(e.currentTarget);
                    var $component = getComponentElement(e.currentTarget);
                    var component = $component.data('component');
                    component.setOperator($option.val());
                    component.createEditorsDOM($component.find('.js-value').first());
                })
                .on('click', '.js-component-toggle', function (e) {
                    var $link = $(e.currentTarget);
                    var $icon = $link.find('span');
                    var $component = getComponentElement($link);
                    var component = $component.data('component');
                    var nextIsEnabled = !component.isEnabled();
                    component.setEnabled(nextIsEnabled);

                    if (nextIsEnabled) {
                        $link.attr('title', localizer.getString('DisableFilter'));
                        $icon.addClass('icon-disable').removeClass('icon-enable');
                        if (component.isCondition()) {
                            $component.find('select,input').prop('disabled', false);
                        } else {
                            $component.removeClass('filter-builder-group-disabled');
                        }
                    } else {
                        $link.attr('title', localizer.getString('EnableFilter'));
                        $icon.addClass('icon-enable').removeClass('icon-disable');
                        if (component.isCondition()) {
                            $component.find('select,input').prop('disabled', true);
                        } else {
                            $component.addClass('filter-builder-group-disabled');
                        }
                    }

                    if (self.currentFilterComponent === component) {
                        self.$disableFilterCheckbox.prop('checked', !component.isEnabled());
                    }

                    e.preventDefault();
                })
                .on('change', '.js-filter-builder-disable', function (e) {
                    $modal.find('.js-component .js-component-toggle').first().click();
                }.bind(this))
            ;
        },

        show: function () {
            this.$modal.modal('show');
            this.currentFilterComponent = new Group();
            this.currentFilterComponent.deserialize(this.columns, this.filterComponent.serialize());

            if (this.currentFilterComponent.isEmpty()) {
                this.currentFilterComponent.addChild(new Condition(this._getDefaultColumn()));
            }

            this.$container.html('');
            this.$disableFilterCheckbox.prop('checked', !this.currentFilterComponent.isEnabled());
            this._addComponentToDOM(this.$container, this.currentFilterComponent);
            return this;
        },

        hide: function () {
            this.$modal.modal('hide');
            return this;
        },

        addColumn: function (column) {
            this.columns.push(column);
            return this;
        },

        getColumns: function () {
            return this.columns;
        },

        setFilterComponent: function (filterComponent) {
            this.filterComponent = filterComponent;
            return this;
        },

        setEnabled: function (isEnabled) {
            this.filterComponent.setEnabled(isEnabled);
            return this;
        },

        reset: function () {
            this.filterComponent = new Group();
            return this;
        },

        submit: function () {
            this.$modal.find('[data-editor]').trigger('change');

            var jsonData = JSON.stringify(this.filterComponent.serialize());
            $('<form>').attr('method', 'POST').append(
                $('<input type="hidden" name="filter_builder">').val(jsonData)
            ).hide().appendTo($('body')).submit();
            return this;
        },

        _addComponentToDOM: function ($container, component, isFocused) {
            var $componentDOM = component.getDOM(this.columns);
            $container.append($componentDOM);

            if (isFocused) {
                $componentDOM.find('.js-condition-field-select').first().focus();
            }

            // non-webkit browsers hack
            if (component === this.currentFilterComponent) {
                $componentDOM.find('td').first().removeAttr('colspan');
            }

            return $componentDOM;
        },

        _getDefaultColumn: function () {
            return _.first(this.columns);
        },

        _getColumnByName: function (columnName) {
            return _.find(this.columns, function (column) {
                return column.getName() === columnName;
            });
        }

    });

    var Group = Filter.Group.extend({
        createCondition: function () {
            return new Condition();
        },

        getDOM: function (columns) {
            var $el = $(createTemplate('group')({
                isEnabled: this.enabled,
                operator: localizer.getString(
                    'Operator'
                    + this.operator[0]
                    + this.operator.toLowerCase().slice(1)
                )
            })).data('component', this);

            var $container = $el.find('.js-group-content').first();
            _.each(this.children, function (child) {
                $container.append(child.getDOM(columns));
            });

            return $el;
        }
    });

    var Condition = Filter.Condition.extend({
        setOperator: function (operator) {
            var valuesCount = getValuesCount(this.column, operator);
            if (this.editors && this.editors[0] && !this.editors[0].isMultivalue()) {
                this
                    .setValues(this.values.slice(0, valuesCount))
                    .setDisplayValues(this.displayValues.slice(0, valuesCount));
            }
            return this._super(operator);
        },

        getDOM: function (columns) {
            var $el = $(createTemplate('condition')({
                isEnabled: this.enabled,
                columnName: this.column.getName(),
                operator: this.operator,
                columns: columns,
                operators: _.reduce(this.column.getOperators(), function (acc, operator) {
                    acc[operator] = localizer.getString('FilterOperator' + operator);
                    return acc;
                }, {}),
            })).data('component', this);

            this.createEditorsDOM($el.find('.js-value').first());

            return $el;
        },

        createEditorsDOM: function ($container) {
            $container.html('');
            var editorsCount = getEditorsCount(this.column, this.operator);
            if (editorsCount > 1) {
                $container.addClass('filter-builder-condition-value-multiple');
            } else {
                $container.removeClass('filter-builder-condition-value-multiple');
            }

            this.editors = [];
            for (var i = 0; i < editorsCount; i++) {
                var $editorTemplate = getColumnEditorElement(this.operator, this.column);

                if ($editorTemplate.length === 0) {
                    continue;
                }

                var $editorWrapper = $('<div>').append($editorTemplate);
                $container.append($editorWrapper);

                var editorIndex = i;
                var $editorElement = $editorWrapper.find('[data-editor]').first();
                var EditorClass = editors.getEditorByName($editorElement.data('editor'));
                var editor = new EditorClass($editorElement);
                this.editors[i] = editor;
                editor.setEnabled(this.isEnabled());

                exchangeValues(editor, this, editorIndex);

                editor.onChange(_.partial(setConditionValues, editor, this, editorIndex));

                if (editorsCount > 1 && i !== editorsCount - 1) {
                    $container.append(
                        $('<div>')
                            .addClass('filter-builder-condition-value-divider')
                            .text(localizer.getString('And'))
                    );
                }
            }
            return this;
        }
    });

    function exchangeValues(editor, condition, index) {
        if (editor.isMultivalue()) {
            var values = condition.getValues();
            if (values) {
                editor.setValueEx(values);
            } else {
                setConditionValues(editor, condition, index);
            }
        } else {
            var value = condition.getValueAt(index);
            if (value !== null) {
                editor.setValueEx(value);
            } else {
                setConditionValues(editor, condition, index);
            }
        }
    }

    function setConditionValues(editor, condition, index) {
        if (editor.isMultivalue()) {
            condition
                .setValues(editor.getValueEx())
                .setDisplayValues(editor.getDisplayValue());
        } else {
            condition
                .setValueAt(index, editor.getValueEx())
                .setDisplayValueAt(index, editor.getDisplayValue());
        }
    }

    function getColumnEditorElement(operator, column) {
        return $($(
            '#filter_builder_editor_' + operator + '_' + column.getName().replace(/ /g, '_')
        ).html());
    }

    var templateSelectors = {
        condition: '#filterBuilderConditionTemplate',
        group: '#filterBuilderGroupTemplate'
    };

    var templates = {};

    function createTemplate(name) {
        if (templates[name]) {
            return templates[name]
        }

        var template = _.template($(templateSelectors[name]).html());
        templates[name] = template;

        return template;
    }

    function getComponentElement(element) {
        return $(element).closest('.js-component');
    }

    function getEditorsCount(column, operator) {
        switch (operator) {
            case Filter.ConditionOperator.IS_BETWEEN:
            case Filter.ConditionOperator.IS_NOT_BETWEEN:
                return 2;
            case Filter.ConditionOperator.IS_BLANK:
            case Filter.ConditionOperator.IS_NOT_BLANK:
            case Filter.ConditionOperator.TODAY:
                return 0;
            default:
                return 1;
        }
    }

    function getValuesCount(column, operator) {
        switch (operator) {
            case Filter.ConditionOperator.IN:
            case Filter.ConditionOperator.NOT_IN:
                return Infinity;
            default:
                return getEditorsCount(column, operator);
        }
    }

    return {
        Group: Group,
        Condition: Condition,
        create: function (id, $modal) {
            return new Builder(id, $modal);
        }
    };
});
