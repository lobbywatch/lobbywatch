define([
    'class',
    'underscore'
], function (Class, _) {

    var Component = Class.extend({
        init: function (operator) {
            this.operator = operator;
            this.enabled = true;
        },

        isEnabled: function () {
            return this.enabled;
        },

        setEnabled: function (enabled) {
            this.enabled = enabled;
            return this;
        },

        getOperator: function () {
            return this.operator;
        },

        setOperator: function (operator) {
            this.operator = operator;
            return this;
        },

        isGroup: function () {
            return false;
        },

        isCondition: function () {
            return false;
        }
    });

    var Group = Component.extend({
        init: function (operator, children) {
            this._super(operator || GroupOperator.OPERATOR_AND);
            this.children = children || {};
        },

        setChildren: function (children) {
            this.children = children;
            return this;
        },

        addChild: function (child, index) {
            index = index || Date.now();
            this.children[index] = child;
            return this;
        },

        removeChild: function (childToRemove) {
            var key = _.findKey(this.children, function (child) {
                return child === childToRemove;
            });

            if (!_.isUndefined(key)) {
                delete this.children[key];
            } else {
                _.each(this.children, function (child) {
                    child.removeChild(childToRemove);
                });
            }

            return this;
        },

        getChildren: function () {
            return this.children;
        },

        getChild: function (key) {
            return this.children[key];
        },

        deserialize: function (columns, data) {
            return this
                .setOperator(data.operator)
                .setEnabled(data.isEnabled)
                .setChildren(_.mapObject(data.children, _.bind(function (childData) {
                    var child = childData.type === ComponentType.GROUP
                        ? new this.constructor()
                        : this.createCondition()

                    return child.deserialize(columns, childData);
                }, this)));
        },

        serialize: function () {
            return {
                type: ComponentType.GROUP,
                isEnabled: this.isEnabled(),
                operator: this.getOperator(),
                children: _.mapObject(this.children, function (child) {
                    return child.serialize();
                })
            };
        },

        isEmpty: function () {
            if (_.find(this.children, function (child) {
                return !child.isEmpty()
            })) {
                return false;
            };

            return true;
        },

        createCondition: function () {
            return new Condition();
        },

        isGroup: function () {
            return true;
        }
    });

    var Condition = Component.extend({
        init: function (column, operator, values) {
            this._super(operator || column ? _.first(column.getOperators()) : ConditionOperator.EQUALS);
            this.column = column;
            this.values = values || [];
            this.displayValues = _.clone(this.values);
        },

        getColumnName: function () {
            return this.column.getName();
        },

        setColumn: function (column) {
            this.column = column;
            this.values = [];
            this.displayValues = [];
            this.setOperator(_.first(column.getOperators()));
            return this;
        },

        getValues: function () {
            return this.values;
        },

        getValueAt: function (index) {
            return typeof(this.values[index]) === 'undefined'
                ? null
                : this.values[index];
        },

        setValues: function (values) {
            this.values = values;
            return this;
        },

        setValueAt: function (index, value) {
            this.values[index] = value;
            return this;
        },

        setDisplayValues: function (values) {
            this.displayValues = values;
            return this;
        },

        setDisplayValueAt: function (index, value) {
            this.displayValues[index] = value;
            return this;
        },

        getDisplayValues: function (values) {
            return this.displayValues;
        },

        getDisplayValueAt: function (index) {
            return typeof(this.displayValues[index]) === 'undefined'
                ? null
                : this.displayValues[index];
        },

        serialize: function () {
            return {
                type: ComponentType.CONDITION,
                isEnabled: this.isEnabled(),
                column: this.column.getName(),
                operator: this.getOperator(),
                values: this.getValues(),
                displayValues: this.getDisplayValues()
            };
        },

        deserialize: function(columns, data) {
            return this
                .setColumn(_.find(columns, function (column) {
                    return column.getName() === data.column;
                }))
                .setOperator(data.operator)
                .setValues(data.values)
                .setDisplayValues(data.displayValues)
                .setEnabled(data.isEnabled);
        },

        isEmpty: function () {
            return false;
        },

        removeChild: function () {
        },

        isCondition: function () {
            return true;
        }
    });

    var Column = Class.extend({

        init: function (name, caption, operators) {
            this.name = name;
            this.caption = caption;
            this.operators = operators;
        },

        getName: function () {
            return this.name;
        },

        getCaption: function () {
            return this.caption;
        },

        getOperators: function () {
            return this.operators;
        }

    });

    var ComponentType = {
        GROUP: 'group',
        CONDITION: 'condition'
    };

    var ConditionOperator = {
        EQUALS: 'Equals',
        DOES_NOT_EQUAL: 'DoesNotEqual',
        IS_GREATER_THAN: 'IsGreaterThan',
        IS_GREATER_THAN_OR_EQUAL_TO: 'IsGreaterThanOrEqualTo',
        IS_LESS_THAN: 'IsLessThan',
        IS_LESS_THAN_OR_EQUAL_TO: 'IsLessThanOrEqualTo',
        IS_BETWEEN: 'IsBetween',
        IS_NOT_BETWEEN: 'IsNotBetween',
        CONTAINS: 'Contains',
        DOES_NOT_CONTAIN: 'DoesNotContain',
        BEGINS_WITH: 'BeginsWith',
        ENDS_WITH: 'EndsWith',
        IS_LIKE: 'IsLike',
        IS_NOT_LIKE: 'IsNotLike',
        IS_BLANK: 'IsBlank',
        IS_NOT_BLANK: 'IsNotBlank',
        DATE_EQUALS: 'DateEquals',
        DATE_DOES_NOT_EQUAL: 'DateDoesNotEqual',
        YEAR_EQUALS: 'YearEquals',
        YEAR_DOES_NOT_EQUAL: 'YearDoesNotEqual',
        MONTH_EQUALS: 'MonthEquals',
        MONTH_DOES_NOT_EQUAL: 'MonthDoesNotEqual',
        IN: 'In',
        NOT_IN: 'NotIn',
        TODAY: 'Today',
        THIS_MONTH: 'ThisMonth',
        PREV_MONTH: 'PrevMonth',
        THIS_YEAR: 'ThisYear',
        PREV_YEAR: 'PrevYear'
    };

    var GroupOperator = {
        OPERATOR_AND: 'AND',
        OPERATOR_OR: 'OR',
        OPERATOR_NONE: 'NONE'
    };

    return {
        Column: Column,
        Group: Group,
        Condition: Condition,
        ComponentType: ComponentType,
        ConditionOperator: ConditionOperator,
        GroupOperator: GroupOperator
    };

});
