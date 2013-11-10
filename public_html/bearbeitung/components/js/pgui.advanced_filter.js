define(function (require, exports, module) {

    var Class = require('class'),
        dtp = require('pgui.datetimepicker'),
        th = require('pgui.typeahead'),
        pgevents = require('pgui.events'),
        localizer = require('pgui.localizer').localizer;


    var FilterItemType = exports.FilterItemType = {
        Group:1,
        Condition:2
    };

    var FieldType = exports.FieldType = {
        Integer:1,
        String:2,
        Blob:3,
        DateTime:4,
        Date:5,
        Time:6,
        Boolean:7
    };

    var GroupOperator = exports.GroupOperator = {
        And:1,
        Or:2,
        Xor:3
    };

    var ConditionOperator = exports.ConditionOperator = {
        Equals:1,
        DoesNotEqual:2,
        IsGreaterThan:3,
        IsGreaterThanOrEqualTo:4,
        IsLessThan:5,
        IsLessThanOrEqualTo:6,
        IsBetween:7,
        IsNotBetween:8,
        Contains:9,
        DoesNotContain:10,
        BeginsWith:11,
        EndsWith:12,
        IsLike:13,
        IsNotLike:14
    };

    var ConditionOperatorImp = Class.extend({
        init:function (imageClass, caption, operator, menuItemClass) {
            this.imageClass = imageClass;
            this.caption = caption;
            this.operator = operator;
            this.menuItemClass = menuItemClass;
        }
    });

    var conditionOperatorImps = {};
    conditionOperatorImps[ConditionOperator.Equals] =
        new ConditionOperatorImp('operator-equals-image', localizer.getString('FilterOperatorEquals'), ConditionOperator.Equals, 'operator-equals');
    conditionOperatorImps[ConditionOperator.DoesNotEqual] =
        new ConditionOperatorImp('operator-does-not-equal-image', localizer.getString('FilterOperatorDoesNotEqual'), ConditionOperator.DoesNotEqual, 'operator-does-not-equal');
    conditionOperatorImps[ConditionOperator.IsGreaterThan] =
        new ConditionOperatorImp('operator-is-greater-than-image', localizer.getString('FilterOperatorIsGreaterThan'), ConditionOperator.IsGreaterThan, 'operator-equals');
    conditionOperatorImps[ConditionOperator.IsGreaterThanOrEqualTo] =
        new ConditionOperatorImp('operator-is-greater-than-or-equal-to-image', localizer.getString('FilterOperatorIsGreaterThanOrEqualTo'), ConditionOperator.IsGreaterThanOrEqualTo, 'operator-equals');
    conditionOperatorImps[ConditionOperator.IsLessThan] =
        new ConditionOperatorImp('operator-is-less-than-image', localizer.getString('FilterOperatorIsLessThan'), ConditionOperator.IsLessThan, 'operator-equals');
    conditionOperatorImps[ConditionOperator.IsLessThanOrEqualTo] =
        new ConditionOperatorImp('operator-is-less-than-or-equal-to-image', localizer.getString('FilterOperatorIsLessThanOrEqualTo'), ConditionOperator.IsLessThanOrEqualTo, 'operator-equals');
    conditionOperatorImps[ConditionOperator.IsBetween] =
        new ConditionOperatorImp('operator-is-between-image', localizer.getString('FilterOperatorIsBetween'), ConditionOperator.IsBetween, 'operator-equals');
    conditionOperatorImps[ConditionOperator.IsNotBetween] =
        new ConditionOperatorImp('operator-is-not-between-image', localizer.getString('FilterOperatorIsNotBetween'), ConditionOperator.IsNotBetween, 'operator-equals');
    conditionOperatorImps[ConditionOperator.Contains] =
        new ConditionOperatorImp('operator-contains-image', localizer.getString('FilterOperatorContains'), ConditionOperator.Contains, 'operator-equals');
    conditionOperatorImps[ConditionOperator.DoesNotContain] =
        new ConditionOperatorImp('operator-does-not-contain-image', localizer.getString('FilterOperatorDoesNotContain'), ConditionOperator.DoesNotContain, 'operator-equals');
    conditionOperatorImps[ConditionOperator.BeginsWith] =
        new ConditionOperatorImp('operator-begins-with-image', localizer.getString('FilterOperatorBeginsWith'), ConditionOperator.BeginsWith, 'operator-equals');
    conditionOperatorImps[ConditionOperator.EndsWith] =
        new ConditionOperatorImp('operator-ends-with-image', localizer.getString('FilterOperatorEndsWith'), ConditionOperator.EndsWith, 'operator-equals');
    conditionOperatorImps[ConditionOperator.IsLike] =
        new ConditionOperatorImp('operator-is-like-image', localizer.getString('FilterOperatorIsLike'), ConditionOperator.IsLike, 'operator-equals');
    conditionOperatorImps[ConditionOperator.IsNotLike] =
        new ConditionOperatorImp('operator-is-not-like-image', localizer.getString('FilterOperatorIsNotLike'), ConditionOperator.IsNotLike, 'operator-equals');

    var getGroupOperatorAsString = function (operator) {
        if (operator == GroupOperator.And)
            return localizer.getString('And');
        else if (operator == GroupOperator.Or)
            return localizer.getString('Or');
        else if (operator == GroupOperator.Xor)
            return localizer.getString('Xor');
        return '';
    };

    var getOperatorAsString = function (operator) {
        return conditionOperatorImps[operator].caption;
    };

    var FilterCondition = exports.FilterCondition = Class.extend({

        init:function (fieldName, fieldType, operator, value) {
            this.fieldName = fieldName;
            this.fieldType = fieldType;
            this.operator = operator;
            this.value = value;
            this.type = FilterItemType.Condition;
            this.displayValue = value;
        },

        setFieldName:function (value) {
            this.fieldName = value;
        },

        getOperator:function () {
            return this.operator;
        },

        setOperator:function (value) {
            this.operator = value;
        },

        getValue:function () {
            return this.value;
        },

        setValue:function (value) {
            this.value = value;
        },

        setDisplayValue:function (value) {
            this.displayValue = value;
        },

        getDisplayValue:function (value) {
            return this.displayValue;
        },

        getFieldName:function () {
            return this.fieldName;
        },

        getOperatorAsString:function () {
            return getOperatorAsString(this.getOperator());
        },

        serialize:function () {
            return {
                type:FilterItemType.Condition,
                fieldName:this.getFieldName(),
                operator:this.getOperator(),
                values:[ this.getValue() ],
                displayValue:this.getDisplayValue()
            };
        },

        findItemParent:function (item) {
            return null;
        },

        asString:function () {
            return this.getFieldName() + ' ' + this.getOperatorAsString() + ' ' + this.getValue();
        },

        isEmpty:function () {
            return false;
        },

        deserialize:function (data) {
            this.type = FilterItemType.Condition;
            this.setFieldName(data.fieldName);
            this.setOperator(data.operator);
            this.setValue(data.values[0]);
            this.setDisplayValue(data.displayValue)
        }
    });

    var FilterGroup = exports.FilterGroup = Class.extend({

        init:function () {
            this.type = FilterItemType.Group;
            this.items = [];
            this.operator = GroupOperator.And;
        },

        add:function (item) {
            this.items.push(item);
        },

        remove:function (item) {
            for (var i = 0; i < this.items.length; i++) {
                if (item === this.items[i]) {
                    this.items.splice(i, 1);
                }
            }
        },

        getOperatorAsString:function () {
            return getGroupOperatorAsString(this.getOperator());
        },

        getOperator:function () {
            return this.operator;
        },

        setOperator:function (value) {
            this.operator = value;
        },

        getItems:function () {
            return this.items;
        },

        getItem:function (index) {
            return this.items[index];
        },

        deserialize:function (data) {
            this.operator = data.operator;
            this.type = FilterItemType.Group;
            for (var i = 0; i < data.items.length; i++) {
                var item = null;
                if (data.items[i].type == FilterItemType.Condition) {
                    item = new FilterCondition();
                }
                else if (data.items[i].type == FilterItemType.Group) {
                    item = new FilterGroup();
                }
                item.deserialize(data.items[i]);
                this.add(item);
            }
        },

        serialize:function () {
            var items = [];
            for (var i = 0; i < this.getItems().length; i++)
                items.push(this.getItems()[i].serialize());
            return {
                type:FilterItemType.Group,
                operator:this.getOperator(),
                items:items
            };
        },

        findItemParent:function (item) {
            for (var i = 0; i < this.items.length; i++) {
                if (this.items[i] === item)
                    return this;
            }
            for (i = 0; i < this.items.length; i++) {
                var parentItem = this.items[i].findItemParent(item);
                if (parentItem)
                    return parentItem;
            }
            return null;
        },

        asString:function () {
            var result = '';
            var items = this.getItems();
            for (var i = 0; i < items.length; i++) {
                if (i > 0)
                    result += ' ' + this.getOperatorAsString() + ' ';
                if (items.length > 1)
                    result += '(';
                result += items[i].asString();
                if (items.length > 1)
                    result += ')';
            }
            return result;
        },

        isEmpty:function () {
            var items = this.getItems();
            for (var i = 0; i < items.length; i++)
                if (!items[i].isEmpty())
                    return false;
            return true;
        }

    });

    var Filter = exports.Filter = Class.extend({
        init:function () {
            this.root = new FilterGroup();
        },

        getItems:function () {
            return [
                this.root
            ];
        },

        getItem:function (index) {
            return this.root;
        },

        asJson:function () {
            return JSON.stringify(this.root.serialize(), '\t', '  ');
        },

        fromJson:function (data) {
            return this.root.deserialize(data);
        },

        isEmpty:function () {
            return this.root.isEmpty();
        },

        asString:function () {
            return this.root.asString();
        },

        findItemParent:function (item) {
            return this.root.findItemParent(item);
        }
    });

    exports.DateEdit = exports.DateTimeEdit = Class.extend({
        init:function (container, options) {
            var self = this;
            this.container = container;
            this.options = options;
            self.datePickerControl = null;
            self.$editor = this._initializeEditor();
        },

        destory:function () {
            this.$editor.remove();
        },

        _initializeEditor:function () {
            var $editor =
                $('<input>')
                    .attr('type', 'text')
                    .attr('data-calendar', 'true')
                    .appendTo(this.container);
            if (this.options || this.options.fdow) {
                $editor.attr('data-picker-first-day-of-week', this.options.fdow)
            }
            this.datePickerControl = new dtp.DateTimePicker($editor);

            return $editor;
        },

        setValue:function (value) {
            this.$editor.val(value);
        },

        getValue:function () {
            return this.$editor.val();
        },

        getDisplayValue:function () {
            return this.$editor.val();
        },

        setDisplayValue:function (value) {
            this.$editor.val(value);
        },

        onChange:function (callback) {
            this.$editor.change(callback);
            this.datePickerControl.onChange(callback);
        }
    });

    exports.TextEdit = Class.extend({
        init:function (container) {
            this.container = container;
            this.$editor = this._initializeEditor();
        },

        destory:function () {
            this.$editor.remove();
        },

        _initializeEditor:function () {
            return $('<input>')
                .attr('type', 'text')
                .appendTo(this.container);
        },

        setValue:function (value) {
            this.$editor.val(value);
        },

        setDisplayValue:function (value) {
            this.$editor.val(value);
        },

        getDisplayValue:function () {
            return this.$editor.val();
        },

        getValue:function () {
            return this.$editor.val();
        },

        onChange:function (callback) {
            this.$editor.change(callback);
        }
    });

    exports.Typeahead = Class.extend({
        init:function (container, options) {
            this.container = container;
            this.options = options;
            this.$editor = this._initializeEditor();
        },

        destory:function () {
            this.$editor.remove();
        },

        _initializeEditor:function () {
            var $editor =
                $('<input>')
                    .attr('data-pg-typeahead-handler', this.options.handler)
                    .attr('type', 'text')
                    .appendTo(this.container);
            (new th.PgTypeahead($editor));
            pgevents.setupInputEvents($editor);
            return $editor;
        },

        setValue:function (value) {
            this.$editor.val(value);
        },

        getValue:function () {
            return this.$editor.attr('data-post-value');
        },

        setDisplayValue:function (value) {
            this.$editor.val(value);
        },

        getDisplayValue:function () {
            return this.$editor.val();
        },

        onChange:function (callback) {
            this.$editor.change(callback);
            this.$editor.data('pg-events').onChange(callback);
        }
    });

    exports.FilterBuilder = Class.extend({
        init:function (container, filter) {
            this.container = container;
            this.root = filter || (new Filter());
            this.fields = {};
            this.fieldNames = [];

            this.groupMenu =
                $('<div class="group-menu" id="menu">' +
                    '<div class="action operator-and"><span class="filter-image operator-and-image"></span>' + localizer.getString('And') + '</div>' +
                    '<div class="action operator-or"><span class="filter-image operator-or-image"></span>' + localizer.getString('Or') + '</div>' +
                    '<div class="action operator-xor"><span class="filter-image operator-xor-image"></span>' + localizer.getString('Xor') + '</div>' +
                    '<div class="separator"></div>' +
                    '<div class="action action-add-group"><span class="filter-image action-add-group-image"></span>' + localizer.getString('AddGroup') + '</div>' +
                    '<div class="action action-add-condition"><span class="filter-image action-add-condition-image"></span>' + localizer.getString('AndCondition') + '</div>' +
                    '<div class="separator"></div>' +
                    '<div class="action action-remove"><span class="filter-image action-remove-image"></span>' + localizer.getString('RemoveFromFilter') + '</div>' +
                    '</div>').appendTo($('body'));

            this.operatorMenu = $('<div class="group-menu"></div>').appendTo($('body'));
            this.fieldNameMenu = $('<div>')
                .addClass('group-menu')
                .appendTo($('body'));
            var self = this;

            self.operatorMenu.children('.action').click(function (event) {
                event.stopPropagation();
                self._operatorMenuActionClickHandler(self.currentOpenedFilterItem, $(this));
                self.operatorMenu.hide();
                self.currentOpenedFilterItem = null;
            });

            self.groupMenu.children('.action').click(function (event) {
                event.stopPropagation();
                self._groupMenuActionClickHandler(self.currentOpenedGroup, $(this));
                self.groupMenu.hide();
                self.currentOpenedGroup = null;
            });

            $('body,html').click(function () {
                self.fieldNameMenu.hide();
                self.groupMenu.hide();
                self.currentOpenedGroup = null;
                self.operatorMenu.hide();
            });
        },

        getFilter:function () {
            return this.root;
        },

        _createEditorForField:function (fieldName, container) {
            return new this.fields[fieldName].editorClass(container, this.fields[fieldName].editorOptions);
        },

        _addItemIntoMenu:function (menu, caption, menuItemClass, data, clickCallback) {
            var self = this;
            var $menuItem = $('<div>')
                .addClass(menuItemClass)
                .text(caption)
                .click(function (event) {
                    event.stopPropagation();
                    self._fieldMenuActionClickHandler(self.currentOpenedFilterItem, $(this));
                    menu.hide();
                    self.currentOpenedFilterItem = null;
                })
                .appendTo(menu);

            for (var dataName in data) {
                if (data.hasOwnProperty(dataName))
                    $menuItem.data(dataName, data[dataName]);
            }
        },

        _addConditionItem:function (itemContainer, conditionItem) {
            var self = this;

            var $condition = $('<div>')
                .addClass('condition');

            var $fieldName = $('<span>')
                .addClass('field-name')
                .html(this._getCaptionByFieldName(conditionItem.getFieldName()))
                .click(function (event) {
                    event.stopPropagation();
                    var $fieldName = $(this);
                    var filterItem = $fieldName.closest('.condition').data('filter-item');
                    self._showSelectFieldNameMenu($fieldName, filterItem);
                })
                .appendTo($condition);

            var $operator = $('<span>')
                .addClass('operator')
                .html(conditionItem.getOperatorAsString())
                .click(function (event) {
                    event.stopPropagation();
                    var $operator = $(this);
                    var filterItem = $operator.closest('.condition').data('filter-item');
                    self._showSelectOperatorMenu($operator, filterItem);
                })
                .appendTo($condition);


            var $value = $('<span>')
                .addClass('value')
                .appendTo($condition);

            var editor = this._createEditorForField(conditionItem.getFieldName(), $value);
            editor.setValue(conditionItem.getValue());
            editor.setDisplayValue(conditionItem.getDisplayValue());


            editor.onChange(function () {
                conditionItem.setValue(editor.getValue());
                conditionItem.setDisplayValue(editor.getDisplayValue());
            });

            var $deleteButton = $('<span>')
                .addClass('remove-condition')
                .click(function () {
                    self.removeItem(conditionItem);
                })
                .appendTo($condition);


            itemContainer.append($condition);
            $condition.data('filter-item', conditionItem);
            $condition.data('editor', editor);
        },

        _hideMenus:function () {
            this.fieldNameMenu.hide();
            this.groupMenu.hide();
            this.operatorMenu.hide();
        },

        _fieldMenuActionClickHandler:function (filterItem, $fieldNameMenuItem) {
            filterItem.setFieldName($fieldNameMenuItem.data('field-name'));
            this._updateItem(filterItem);
        },

        _operatorMenuActionClickHandler:function (filterItem, $operatorMenuItem) {
            filterItem.setOperator($operatorMenuItem.data('condition-operator'));
            this._updateItem(filterItem);
        },

        _showSelectFieldNameMenu:function ($fieldName, filterItem) {
            var self = this;
            this._hideMenus();
            self.currentOpenedFilterItem = filterItem;
            self.fieldNameMenu.css('top', $fieldName.offset().top + $fieldName.height());
            self.fieldNameMenu.css('left', $fieldName.offset().left);
            self.fieldNameMenu.show();
        },

        _showSelectOperatorMenu:function ($operator, filterItem) {
            var self = this;
            this._hideMenus();
            self.currentOpenedFilterItem = filterItem;
            self.operatorMenu.css('top', $operator.offset().top + $operator.height());
            self.operatorMenu.css('left', $operator.offset().left);
            self.operatorMenu.children('*').remove();
            self._fillConditionOperatorMenu(self.operatorMenu, filterItem);
            self.operatorMenu.show();
        },

        _fillConditionOperatorMenu:function (menu, filterItem) {
            var self = this;

            var availableOperators = this._getAvailableOperatorsForField(filterItem.getFieldName());
            for (var i = 0; i < availableOperators.length; i++) {
                var $menuItemImage = $('<span>')
                    .addClass('filter-image')
                    .addClass(conditionOperatorImps[availableOperators[i]].imageClass);
                var $menuItem = $('<div>')
                    .addClass('action')
                    .addClass(conditionOperatorImps[availableOperators[i]].menuItemClass)
                    .append($menuItemImage)
                    .append(conditionOperatorImps[availableOperators[i]].caption)
                    .appendTo(menu)
                    .data('condition-operator', conditionOperatorImps[availableOperators[i]].operator)
                    .click(function (event) {
                        event.stopPropagation();
                        self._operatorMenuActionClickHandler(self.currentOpenedFilterItem, $(this));
                        self.operatorMenu.hide();
                        self.currentOpenedFilterItem = null;
                    });
            }
        },

        _getAvailableOperatorsByFieldType:function (fieldType) {
            switch (fieldType) {
                case FieldType.Integer:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo,
                        //ConditionOperator.IsBetween,
                        //ConditionOperator.IsNotBetween,
                        ConditionOperator.IsLike,
                        ConditionOperator.IsNotLike
                    ];
                    break;
                case FieldType.String:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo,
                        //ConditionOperator.IsBetween,
                        //ConditionOperator.IsNotBetween,
                        ConditionOperator.Contains,
                        ConditionOperator.DoesNotContain,
                        ConditionOperator.BeginsWith,
                        ConditionOperator.EndsWith,
                        ConditionOperator.IsLike,
                        ConditionOperator.IsNotLike
                    ];
                    break;
                case FieldType.Boolean:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual
                    ];
                    break;
                case FieldType.Date:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo
                    ];
                    break;
                case FieldType.DateTime:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo
                    ];
                    break;
                case FieldType.Time:
                    return [
                        ConditionOperator.Equals,
                        ConditionOperator.DoesNotEqual,
                        ConditionOperator.IsGreaterThan,
                        ConditionOperator.IsGreaterThanOrEqualTo,
                        ConditionOperator.IsLessThan,
                        ConditionOperator.IsLessThanOrEqualTo
                    ];
                    break;
                case FieldType.Blob:
                    return [
                        ConditionOperator.Contains,
                        ConditionOperator.DoesNotContain,
                        ConditionOperator.IsLike,
                        ConditionOperator.IsNotLike
                    ];
                    break;
            }
        },

        _getAvailableOperatorsForField:function (fieldName) {
            var fieldType = this.fields[fieldName].fieldType;
            return this._getAvailableOperatorsByFieldType(fieldType);
        },

        _addGroupItem:function (itemContainer, groupItem) {

            var self = this;

            var $group = $('<div>').addClass('group');
            $group.data('filter-item', groupItem);

            itemContainer.append($group);

            var $groupTitle = $('<div>')
                .addClass('group-title')
                .appendTo($group);

            var $groupOperator = $('<span>')
                .addClass('group-operator')
                .html(groupItem.getOperatorAsString())
                .appendTo($groupTitle);

            $groupOperator.click(function (event) {
                event.stopPropagation();
                self._hideMenus();

                self.currentOpenedGroup = $(this).closest('.group');

                self.groupMenu.css('top', $groupTitle.offset().top + $groupTitle.height());
                self.groupMenu.css('left', $groupTitle.offset().left);
                self.groupMenu.show();
            });

            var $groupContent = $('<div>').addClass('group-content').appendTo($group);

            this._addItems($groupContent, groupItem.getItems());

        },

        _groupMenuActionClickHandler:function (group, actionItem) {
            if (actionItem.hasClass('action-add-condition')) {

                this.addSubItem(group,
                    new FilterCondition(
                        this.fields[this.fieldNames[0]].name,
                        this.fields[this.fieldNames[0]].fieldType, ConditionOperator.Equals, null)
                );
            }
            else if (actionItem.hasClass('action-add-group')) {
                this.addSubItem(group,
                    new FilterGroup()
                );
            }
            else if (actionItem.hasClass('operator-and')) {
                var groupFilterItem = this._getFilterItemByItem(group);
                groupFilterItem.setOperator(GroupOperator.And);
                this._updateItem(groupFilterItem);
            }
            else if (actionItem.hasClass('operator-or')) {
                groupFilterItem = this._getFilterItemByItem(group);
                groupFilterItem.setOperator(GroupOperator.Or);
                this._updateItem(groupFilterItem);
            }
            else if (actionItem.hasClass('operator-xor')) {
                groupFilterItem = this._getFilterItemByItem(group);
                groupFilterItem.setOperator(GroupOperator.Xor);
                this._updateItem(groupFilterItem);
            }
            else if (actionItem.hasClass('action-remove')) {
                groupFilterItem = this._getFilterItemByItem(group);
                this.removeItem(groupFilterItem);
            }
        },

        _addItem:function (itemContainer, item) {
            if (item.type == FilterItemType.Group) {
                this._addGroupItem(itemContainer, item);
            }
            else if (item.type == FilterItemType.Condition) {
                this._addConditionItem(itemContainer, item);
            }
        },

        _addItems:function (itemContainer, items) {
            for (var i = 0; i < items.length; i++) {
                this._addItem(itemContainer, items[i]);
            }
        },

        _updateItem:function (filterItem) {
            if (filterItem.type == FilterItemType.Condition) {
                var $item = this.findItemByFilterItem(filterItem);

                if ($item.children('.field-name').text() != this._getCaptionByFieldName(filterItem.getFieldName())) {
                    $item.data('editor').destory();

                    var $value = $item.children('.value');
                    var editor = this._createEditorForField(filterItem.getFieldName(), $value);
                    editor.setValue(filterItem.getValue());
                    $item.data('editor', editor);
                    editor.onChange(function () {
                        filterItem.setValue(editor.getValue());
                        filterItem.setDisplayValue(editor.getDisplayValue());
                    });

                    var availableOperators = this._getAvailableOperatorsForField(filterItem.getFieldName());
                    if (availableOperators) {
                        filterItem.setOperator(availableOperators[0]);
                    }
                }
                $item.children('.operator').html(filterItem.getOperatorAsString());

                $item.children('.field-name').html(this._getCaptionByFieldName(filterItem.getFieldName()));
            }
            else {
                $item = this.findItemByFilterItem(filterItem);
                $item.children('.group-title').children('.group-operator').html(filterItem.getOperatorAsString());
            }
        },

        /**
         * @param {string} name
         * @return {string}
         * @private
         */
        _getCaptionByFieldName: function(name) {
            return this.fields[name].caption;
        },

        addField:function (name, caption, fieldType, editorClass, editorOptions) {
            this._addItemIntoMenu(this.fieldNameMenu, caption, 'action', {
                'field-name':name
            });
            this.fieldNames.push(name);
            this.fields[name] = {
                name:name,
                caption:caption,
                fieldType:fieldType,
                editorClass:editorClass,
                editorOptions:editorOptions
            };
        },

        getRootItem:function () {
            return this.container.children('.group');
        },

        findItemByFilterItem:function (filterItem) {
            return this.container.find('.group, .condition').filter(function () {
                return $(this).data('filter-item') == filterItem;
            });
        },

        _getFilterItemByItem:function ($item) {
            return $item.data('filter-item');
        },

        removeItem:function (filterItem) {
            var parent = this.root.findItemParent(filterItem);
            if (filterItem.type == FilterItemType.Condition) {
                if (parent) {
                    var $item = this.findItemByFilterItem(filterItem);
                    $item.remove();
                    parent.remove(filterItem);
                }
            }
            else {
                if (filterItem === this.root.getItem(0)) {
                    for (var i = this.root.getItem(0).getItems().length - 1; i >= 0; i--) {
                        this.removeItem(this.root.getItem(0).getItems()[i]);
                    }
                }
                else {
                    if (parent) {
                        var $groupItem = this.findItemByFilterItem(filterItem);
                        $groupItem.remove();
                        parent.remove(filterItem);
                    }
                }
            }
        },

        addSubItem:function ($parent, item) {
            var parentFilterItem = this._getFilterItemByItem($parent);
            parentFilterItem.add(item);
            this._addItem($parent.children('.group-content'), item);
        },

        activate:function () {
            this.container.addClass('pgui-filter-builder');
            this._addItems(this.container, this.root.getItems());
        }
    });

});