<?php

interface IFilterable
{
    function AddFieldFilter($fieldName, $fieldFilter);

    function RemoveFieldFilter($fieldName, $fieldFilter);

    function AddCompositeFieldFilter($filterLinkType, $fieldNames, $fieldFilters);

    function AddCustomCondition($condition);

    function ClearFieldFilters();
}
