<?php

class FilterConditionOperator {
    const EQUALS = 'Equals';
    const DOES_NOT_EQUAL = 'DoesNotEqual';
    const IS_GREATER_THAN = 'IsGreaterThan';
    const IS_GREATER_THAN_OR_EQUAL_TO = 'IsGreaterThanOrEqualTo';
    const IS_LESS_THAN = 'IsLessThan';
    const IS_LESS_THAN_OR_EQUAL_TO = 'IsLessThanOrEqualTo';
    const IS_BETWEEN = 'IsBetween';
    const IS_NOT_BETWEEN = 'IsNotBetween';
    const CONTAINS = 'Contains';
    const DOES_NOT_CONTAIN = 'DoesNotContain';
    const BEGINS_WITH = 'BeginsWith';
    const ENDS_WITH = 'EndsWith';
    const IS_LIKE = 'IsLike';
    const IS_NOT_LIKE = 'IsNotLike';
    const IS_BLANK = 'IsBlank';
    const IS_NOT_BLANK = 'IsNotBlank';
    const DATE_EQUALS = 'DateEquals';
    const DATE_DOES_NOT_EQUAL = 'DateDoesNotEqual';
    const YEAR_EQUALS = 'YearEquals';
    const YEAR_DOES_NOT_EQUAL = 'YearDoesNotEqual';
    const MONTH_EQUALS = 'MonthEquals';
    const MONTH_DOES_NOT_EQUAL = 'MonthDoesNotEqual';
    const IN = 'In';
    const NOT_IN = 'NotIn';
    const TODAY = 'Today';
    const THIS_MONTH = 'ThisMonth';
    const PREV_MONTH = 'PrevMonth';
    const THIS_YEAR = 'ThisYear';
    const PREV_YEAR = 'PrevYear';
}
