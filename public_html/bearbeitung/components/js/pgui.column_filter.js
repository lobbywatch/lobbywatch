define([
    'class',
    'underscore',
    'pgui.filter_common',
    'jquery.popover'
], function (Class, _, Filter) {

    var ColumnFilter = Class.extend({
        init: function ($container) {
            this.$container = $container;
            this.columns = [];
            this.filterComponent = new Group();
            this.excludedComponents = [];
            this.searchEnabled = [];
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

        setExcludedComponents: function (excludedComponents) {
            this.excludedComponents = excludedComponents;
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

        _attachPopover: function (columnName, $trigger, group, excludedComponents) {
            var self = this;
            group.setEnabled(true);
            var originalGroupData = group.serialize();
            var $content = $(createTemplate('content')());

            var popover = $trigger.webuiPopover({
                content: $content,
                backdrop: true,
                placement: 'auto-bottom',
                trigger: 'manual',
            }).data('plugin_webuiPopover');

            $content
                .on('click', '.js-apply', _.bind(function (e) {
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
                    group.deserialize(self.columns, originalGroupData);
                    popover.$contentElement
                        .find('.js-content').first()
                        .html('')
                        .append(self._getColumnGroupDOM(group, excludedComponents));

                    self._handleSearch(columnName, group, popover.$contentElement);
                })
                .on('shown.webui.popover', function () {
                    updateSelectAll(popover.$contentElement);
                });

            return $content;
        },

        setSearchEnabled: function (columnNames) {
            this.searchEnabled = columnNames;
            return this;
        },

        setSearchEnabledFor: function (columnName, isEnabled) {
            var index = this.searchEnabled.indexOf(columnName);

            if (isEnabled && index === -1) {
                this.searchEnabled.push(columnName);
            }

            if (!isEnabled && index !== -1) {
                this.searchEnabled.splice(index, 1);
            }

            return this;
        },

        isSearchEnabledFor: function (columnName) {
            return this.searchEnabled.indexOf(columnName) !== -1;
        },

        _handleSearch: function (columnName, group, $content) {
            var $searchEmpty = $content.find('.js-search-empty').hide();
            var $searchClear = $content.find('.js-search-clear').hide();

            if (!this.isSearchEnabledFor(columnName))  {
                $content.find('.js-search').hide();
                return;
            }

            var $searchInput = $content.find('.js-search-input');
            var titles = group.getTitles();

            $searchClear.on('click', function () {
                $searchInput.val('').trigger('change');
                return false;
            });

            $searchInput.on('keyup change', doSearch);

            function doSearch () {
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

                $searchClear.toggle(value.length > 0);
                updateSelectAll($content);

            }

            doSearch();
        },

        _getColumnGroupDOM: function (group, excludedComponents) {
            return _.toArray(_.mapObject(group.getChildren(), function (child, label) {
                if (!isExcluded(excludedComponents, label)) {
                    return child.getDOM(label, excludedComponents);
                }
            }));
        },

        submit: function () {
            var jsonData = JSON.stringify({
                isEnabled: this.filterComponent.isEnabled(),
                children: this.filterComponent.serializeEnabledComponents()
            });

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
        getDOM: function (label, excludedComponents) {
            var groupExcludedComponents = excludedComponents[label] || [];
            var $el = $(createTemplate('group')({
                label: $('<div/>').html(label).text(),
                checked: this.isEnabled()
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

        serializeEnabledComponents: function () {
            return _.reduce(this.children, function (acc, child, childKey) {
                if (child.isEnabled()) {
                    acc[childKey] = child.serializeEnabledComponents();
                }

                return acc;
            }, {});
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
        }
    });

    var Condition = Filter.Condition.extend({
        init: function (column, operator, values) {
            this._super(column, operator, values);
            this.hasDivider = false;
            this.ignoreSelectAll = null;
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
            return {};
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
        Group: Group,
        Condition: Condition,
        create: function ($header) {
            return new ColumnFilter($header);
        }
    };
});
