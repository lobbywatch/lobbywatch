define([
    'class',
    'underscore',
    'pgui.filter_common',
    'jquery.highlight',
    'jquery.popover'
], function (Class, _, Filter) {

    var ColumnFilter = Class.extend({
        init: function ($container) {
            this.$container = $container;
            this.columns = [];
            this.filterComponent = new Group();
            this.excludedComponents = [];
            this.defaultsEnabled = [];
            this.selectedDateTimeValues = [];
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

        attach: function () {
            _.each(this.filterComponent.getChildren(), _.bind(function (child, columnName) {
                this._attachPopover(
                    columnName,
                    this.$container
                        .find('[data-name="' + columnName + '"] .js-filter-trigger')
                        .first(),
                    child,
                    this.excludedComponents[columnName] || []
                );
            }, this));
        },

        _getChildByColumnName: function(columnName) {
            return _.find(this.filterComponent.getChildren(), function (child, childColumnName) {
                return childColumnName == columnName;
            });
        },

        _attachPopover: function (columnName, $trigger, group, excludedComponents) {
            var self = this;
            var loadedGroup = new Group();
            group.setEnabled(true);
            var $content = $(createTemplate('content')());

            var popover = $trigger.webuiPopover({
                content: $content,
                backdrop: true,
                placement: 'auto-bottom',
                trigger: 'manual',
            }).data('plugin_webuiPopover');

            $content
                .on('click', '.js-apply', _.bind(function (e) {
                    if (self._typeIsDateTime(columnName) && self._isDefaultsEnabledFor(columnName)) {
                        group = loadedGroup;
                        self.filterComponent.addChild(group, columnName);
                    };
                    self._createConditions(columnName, group, popover.$contentElement);
                    this.submit();
                }, this))
                .on('change', '.js-toggle-component', function (e) {
                    updateSelectAll($content);
                })
                .on('change', '.js-select-all', _.bind(function (e) {
                    var checked = $(e.currentTarget).prop('checked');
                    $content.find('input:not(.js-select-all):not(.js-ignore-select-all)')
                        .filter(':visible')
                        .prop('checked', checked)
                        .trigger('change');
                }, this))
                .on('click', '.js-reset', _.bind(function (e) {
                    e.preventDefault();
                    group.setChildren({});
                    this.submit();
                }, this))
                .on('click', '.close', function () {
                    popover.hide();
                });

            $trigger
                .on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    popover.show();
                    $('.webui-popover-backdrop').one('click', function () {
                        popover.hide();
                    });
                })
                .one('click', function (e) {
                    setTimeout(function () {
                        $content.css('width', $content.width());
                    }, 0);
                })
                .on('show.webui.popover', function () {
                    if (self._typeIsDateTime(columnName) && self._isDefaultsEnabledFor(columnName)) {
                        $content.find('.js-search').hide();
                        $content.find('.js-content').hide();
                        $content.find('.js-search-empty').hide();
                        $content.find('.js-searching .js-searching-title').hide();
                        $content.find('.js-searching').show();
                        $.ajax({
                            url: window.location.href,
                            dataType: 'json',
                            data: {
                                column_filter_hname: columnName
                            },
                            success: function (data) {
                                $content.find('.js-searching').hide();
                                $content.find('.js-content').show();
                                loadedGroup.deserialize(self.getColumns(), data);
                                loadedGroup.setEnabled(true);
                                popover.$contentElement
                                    .find('.js-content').first()
                                    .html('')
                                    .append(self._getColumnGroupDOM(loadedGroup, excludedComponents));
                                updateSelectAll(popover.$contentElement);
                            }
                        });
                    } else {
                        popover.$contentElement
                            .find('.js-content').first()
                            .html('')
                            .append(self._getColumnGroupDOM(group, excludedComponents));
                        self._handleSearch(columnName, group, popover.$contentElement);
                    }
                })
                .on('shown.webui.popover', function () {
                    updateSelectAll(popover.$contentElement);
                });

            return $content;
        },

        _typeIsDateTime: function (columnName) {
            return _.find(this.columns, function (column) {
                return ((column.name == columnName) && (column.typeIsDateTime == 1));
            });
        },

        _createConditions: function (columnName, group, $content) {
            var columns = this.columns;
            var column = _.find(columns, function (column) {
                return column.getName() === columnName;
            });

            $content.find('.js-content .js-dynamic input:checked').each(function () {
                var condition = group.createCondition();
                condition.setColumn(column);
                condition
                    .setEnabled(true)
                    .setOperator('Equals')
                    .setValues([$(this).closest('div').data('condition-value')])
                    .setDisplayValues([$(this).closest('div').data('condition-display-value')]);
                group.addChild(condition, ' ' + $(this).closest('div').data('condition-display-value'));
            });

            if (this._typeIsDateTime(columnName)) {
                var resultFullYears = [];
                var resultFullMonths = [];
                var resultDays = [];
                $content.find('.js-content > .js-date-tree').each(function() {
                    var $yearCheckBox = $(this).find('input:checkbox').first();
                    var yearValue = $yearCheckBox.closest('label').attr('title');
                    if ($yearCheckBox.prop('checked')) {
                        resultFullYears.push(yearValue);
                    } else if ($yearCheckBox.prop('indeterminate')) {
                        $(this).find('.js-children > .js-group').each(function() {
                            var $monthCheckBox = $(this).find('input:checkbox').first();
                            var monthValue = $monthCheckBox.closest('label').attr('title');
                            if ($monthCheckBox.prop('checked')) {
                                resultFullMonths.push({'year': yearValue, 'month': monthValue});
                            } else {
                                $(this).find('.js-children > .js-component').each(function() {
                                    var $dayCheckBox = $(this).find('input:checkbox').first();
                                    var dayValue = $dayCheckBox.closest('label').attr('title');
                                    if ($dayCheckBox.prop('checked')) {
                                        resultDays.push({year: yearValue, month: monthValue, day: dayValue});
                                    }
                                });
                            }
                        });
                    }
                });

                if (resultFullYears.length > 0 || resultFullMonths.length > 0 || resultDays.length > 0) {
                    var result = [];
                    result['fullYears'] = resultFullYears;
                    result['fullMonths'] = resultFullMonths;
                    result['days'] = resultDays;
                    this.selectedDateTimeValues[columnName] = result;
                }
            }

        },

        setDefaultsEnabled: function (columnNames) {
            this.defaultsEnabled = columnNames;
            return this;
        },

        _isDefaultsEnabledFor: function (columnName) {
            return this.defaultsEnabled.indexOf(columnName) !== -1;
        },

        _handleSearch: function (columnName, group, $content) {
            $content.find('.js-searching .js-data-loading-title').hide();
            var $searchEmpty = $content.find('.js-search-empty').hide();
            var $searchClear = $content.find('.js-search-clear').hide();
            var $searching = $content.find('.js-searching').hide();

            if (this._typeIsDateTime(columnName) || !this._isDefaultsEnabledFor(columnName)) {
                $content.find('.js-search').hide();
                return;
            }

            var $searchInput = $content.find('.js-search-input');
            var titles = group.getTitles();

            $searchClear.on('click', function () {
                $searchInput.val('').trigger('change');
                return false;
            });

            if ($content.find('.js-component:not(.js-dynamic)').length > 0) {
                $content.find('.js-content').first().append($('<hr class="column-filter-separator">'));
            }

            $searchInput.off('keyup change').on('keyup change', _.debounce(function() {
                doDynamicSearch(false);
            }, 500));
            doDynamicSearch(true);

            function doStaticSearch() {
                $searchEmpty.hide();

                var value = $searchInput.val();
                var components = _.map(_.filter(titles, function (obj) {
                    return obj.title.toLowerCase().indexOf(value.toLowerCase()) !== -1;
                }), function (result) {
                    return result.component;
                });

                if (value.length) {
                    $content.find('.js-component').each(function () {
                        var $component = $(this);
                        var isVisible = components.indexOf($component.data('component')) !== -1;
                        $component.toggle(isVisible);

                        if (isVisible) {
                            $component.parents('.js-children').show();
                            $component.parents('.js-group').show();
                            $component.find('.js-children').first().hide();
                            $component.find('.js-caret').first().removeClass('collapsed');
                        }
                    });

                    $content.find('.js-group').each(function () {
                        var $group = $(this);
                        var hasVisibleChildren = $group.find('.js-component:visible').length > 0;
                        if (hasVisibleChildren) {
                            $group
                                .show()
                                .find('.js-caret:first')
                                .addClass('collapsed');
                        }
                    });

                    if (components.length === 0) {
                        $searchEmpty.show();
                    }
                } else {
                    $content.find('.js-component').show();
                }

                afterSearch();
            }

            function doDynamicSearch(initial) {
                var term = $searchInput.val(),
                    lastTerm = $searchInput.data('last-term');

                if (initial !== true && term === lastTerm) return;

                $searching.show();
                $searchEmpty.hide();
                clearDynamicElements();

                $.ajax({
                    url: window.location.href,
                    dataType: 'json',
                    data: {
                        column_filter_hname: columnName,
                        term: term,
                        excludedValues: getExcludedValues()
                    },
                    success: function (data) {
                        $searching.hide();
                        if (_.isEmpty(data)) {
                            $searchEmpty.show();
                        } else {
                            $content.find('.js-content').first().append(getDynamicConditionsDOM(data));
                        }
                        $searchInput.data('last-term', term);
                        highlightTerm();
                        afterSearch();
                    }
                });

                function clearDynamicElements() {
                    $content.find('.js-content .js-dynamic').remove();
                }

                function getExcludedValues() {
                    var result = [];
                    $content.find('.js-content input:checked').each(function() {
                        result.push($(this).closest('label').attr('title'));
                    });
                    return result;
                }

                function getDynamicConditionsDOM(data) {
                    return _.map(data, function(item) {
                        return getDynamicConditionDOM(item.id, item.value);
                    });
                }

                function getDynamicConditionDOM(value, displayValue) {
                    return $(createTemplate('condition')({
                        ignoreSelectAll: false,
                        hasDivider: false,
                        label: $('<div/>').html(displayValue).text(),
                        checked: false
                    })).addClass('js-dynamic').
                        data('condition-value', value).
                        data('condition-display-value', displayValue);
                }

                function highlightTerm() {
                    $content.find('.js-content .js-dynamic').highlight(term, 'ALL');
                }
            }

            function afterSearch() {
                $searchClear.toggle($searchInput.val().length > 0);
                updateSelectAll($content);
            }

        },

        _getColumnGroupDOM: function (group, excludedComponents) {
            return _.toArray(_.mapObject(group.getChildren(), function (child, label) {
                if (!isExcluded(excludedComponents, label)) {
                    return child.getDOM(label, excludedComponents);
                }
            }));
        },

        submit: function () {
            var jsonData = JSON.stringify(this.filterComponent.serializeEnabledRootComponents(this.selectedDateTimeValues));

            $('<form>').attr('method', 'POST').append(
                $('<input type="hidden" name="column_filter">').val(jsonData)
            ).hide().appendTo($('body')).submit();

            return this;
        },

        setEnabled: function (isEnabled) {
            this.filterComponent.setEnabled(isEnabled);
            return this;
        },

        reset: function () {
            this.filterComponent = new Group();
            return this;
        }
    });

    var Group = Filter.Group.extend({
        init: function (operator, children) {
            this._super(operator, children);
            this.isDateTreePart = false;
        },

        getDOM: function (label, excludedComponents) {
            var groupExcludedComponents = excludedComponents[label] || [];
            var $el = $(createTemplate('group')({
                label: $('<div/>').html(label).text(),
                checked: this.isEnabled(),
                isDateTreePart: this.isDateTreePart
            }));

            var $checkbox = $el.find('.js-toggle-component').first()
            var $children = $el.find('.js-children').first();
            var children = _.filter(_.toArray(_.mapObject(this.getChildren(), function (child, label) {
                if (!isExcluded(groupExcludedComponents, label)) {
                    return child.getDOM(label, groupExcludedComponents);
                }
            })), function (item) {
                return Boolean(item);
            });

            if (children.length === 0) {
                return;
            }

            $children.append(children);

            this._updateEnabledOnDOM($checkbox, $children);

            return $el
                .data('component', this)
                .on('click', '.js-caret:first', function (e) {
                    $(e.currentTarget).toggleClass('collapsed');
                    $children.toggle();
                    e.preventDefault();
                })
                .on('change', '.js-toggle-component', _.bind(function (e) {
                    var component = getComponentElement($(e.currentTarget)).data('component');
                    if (component === this) {
                        $checkbox.prop('indeterminate', false);
                        var checked = $(e.currentTarget).prop('checked');
                        this.setEnabled(checked);
                        $children.find('.js-toggle-component').prop('checked', checked);
                        $children.find('.js-component').each(function (i, child) {
                            $(child).data('component').setEnabled(checked);
                        });
                    } else {
                        this._updateEnabledOnDOM($checkbox, $children);
                    }
                }, this));
        },

        _updateEnabledOnDOM: function ($checkbox, $children, excludedComponents) {
            var checkedChildrenCount = $children.find('.js-toggle-component:checked').length;
            var allChildrenChecked = checkedChildrenCount === $children.find('.js-toggle-component').length;

            $checkbox.prop('indeterminate', checkedChildrenCount > 0 && !allChildrenChecked);
            $checkbox.prop('checked', allChildrenChecked);
            this.setEnabled(checkedChildrenCount > 0);
        },

        createCondition: function () {
            return new Condition();
        },

        serializeEnabledRootComponents: function (selectedDateTimeValues) {
            var self = this;
            return {
                type: 'group',
                isEnabled: this.isEnabled(),
                operator: this.getOperator(),
                children: _.mapObject(this.children, function (child, columnName) {
                    if (child.isEnabled()) {
                        var result = child.serializeEnabledComponents();
                        if (selectedDateTimeValues[columnName] !== undefined) {
                            result.selectedDateTimeValues = self.serializeSelectedDateTimeValues(selectedDateTimeValues[columnName]);
                        }
                        return result;
                    }
                })
            };
        },

        serializeSelectedDateTimeValues: function (selectedDateTimeValues) {
            return {
                fullYears: selectedDateTimeValues['fullYears'],
                fullMonths: selectedDateTimeValues['fullMonths'],
                days: selectedDateTimeValues['days']
            }
        },

        serializeEnabledComponents: function () {
            return {
                type: 'group',
                isEnabled: this.isEnabled(),
                operator: this.getOperator(),
                children: _.mapObject(this.children, function (child) {
                    if (child.isEnabled() && !child.isDateTreePart) {
                        return child.serializeEnabledComponents();
                    }
                })
            };
        },

        setEnabledDeep: function (isEnabled) {
            this.setEnabled(isEnabled);
            _.each(this.children, function (child) {
                child.setEnabledDeep(isEnabled);
            });
            return this;
        },

        getTitles: function () {
            return _.reduce(this.children, function (acc, child, key) {
                acc.push({
                    title: $('<div/>').html(key).text(),
                    component: child
                });

                acc.push(child.getTitles());
                return _.flatten(acc);
            }, []);
        },

        serialize: function () {
            var result = this._super();
            result.isDateTreePart = this.isDateTreePart;
            return result;
        },

        deserialize: function (columns, data) {
            this.isDateTreePart = data.isDateTreePart || false;
            return this._super(columns, data);
        }

    });

    var Condition = Filter.Condition.extend({
        init: function (column, operator, values) {
            this._super(column, operator, values);
            this.hasDivider = false;
            this.ignoreSelectAll = null;
            this.isDateTreePart = false;
        },

        getDOM: function (label) {
            return $(createTemplate('condition')({
                ignoreSelectAll: this.ignoreSelectAll,
                hasDivider: this.hasDivider,
                label: $('<div/>').html(label).text(),
                checked: this.isEnabled()
            }))
            .data('component', this)
            .on('change', '.js-toggle-component', _.bind(function (e) {
                this.setEnabled($(e.currentTarget).prop('checked'));
            }, this));
        },

        serializeEnabledComponents: function () {
            return {
                type: 'condition',
                isEnabled: this.isEnabled(),
                column: this.column.getName(),
                operator: this.getOperator(),
                values: this.getValues(),
                displayValues: this.getDisplayValues()
            };
        },

        setEnabledDeep: function (isEnabled) {
            this.setEnabled(isEnabled);
            return this;
        },

        getTitles: function () {
            return [];
        },

        serialize: function () {
            var result = this._super();
            result.hasDivider = this.hasDivider;
            result.ignoreSelectAll = this.ignoreSelectAll;
            return result;
        },

        deserialize: function(columns, data) {
            this.hasDivider = data.hasDivider || false;
            this.ignoreSelectAll = data.ignoreSelectAll || false;
            return this._super(columns, data);
        }
    });

    var Column = Filter.Column.extend({
        init: function (name, caption, typeIsDateTime, operators) {
            this._super(name, caption, operators);
            this.typeIsDateTime = typeIsDateTime;
        },

        setTypeIsDateTime: function(value) {
            this.typeIsDateTime = value;
            return this;
        },

        getTypeIsDateTime: function() {
            return this.typeIsDateTime;
        }
    });

    var templateSelectors = {
        content: '#columnFilterContentTemplate',
        group: '#columnFilterGroupTemplate',
        condition: '#columnFilterConditionTemplate'
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

    function isExcluded(excluded, key) {
        return _.isObject(excluded[key]) && _.keys(excluded[key]).length === 0;
    }

    function updateSelectAll($content) {
        var checked = $content.find('.js-toggle-component:visible:not(.js-ignore-select-all)').length
            === $content.find('.js-toggle-component:visible:checked:not(.js-ignore-select-all)').length;
        $content.find('.js-select-all').prop('checked', checked);
    }

    return {
        Column: Column,
        Group: Group,
        Condition: Condition,
        create: function ($header) {
            return new ColumnFilter($header);
        }
    };
});
