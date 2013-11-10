<?php

require_once 'components/utils/system_utils.php';
require_once 'components/utils/string_utils.php';


class GenericFilterSQLGenerator {

    /** @var EngCommandImp */
    private $commandImp;

    public function __construct($commandImp) {
        $this->commandImp = $commandImp;
        $this->fields = array();
        $this->legacyGenerator = new FilterConditionGenerator($this->commandImp);
    }

    public final function AddField($name, $fieldType) {
        $this->fields[$name] = $fieldType;
    }

    private function CreateLegacyFilter(FilterCondition $filterCondition) {
        switch ($filterCondition->GetOperator()) {
            case FilterConditionOperator::IsBetween:
            case FilterConditionOperator::IsNotBetween:
                return null;
                break;
            case FilterConditionOperator::Contains:
                return new FieldFilter(
                    '%' . $filterCondition->GetValue(0) . '%',
                    'ILIKE');
                break;
            case FilterConditionOperator::DoesNotContain:
                return
                    new NotPredicateFilter(
                        new FieldFilter(
                            '%' . $filterCondition->GetValue(0) . '%',
                            'ILIKE')
                    );
                break;
            case FilterConditionOperator::BeginsWith:
                return new FieldFilter(
                    $filterCondition->GetValue(0) . '%',
                    'ILIKE');
                break;
            case FilterConditionOperator::EndsWith:
                return new FieldFilter(
                    '%' . $filterCondition->GetValue(0),
                    'ILIKE');
                break;
            case FilterConditionOperator::IsNotLike:
                return
                    new NotPredicateFilter(
                        new FieldFilter(
                            $filterCondition->GetValue(0),
                            'ILIKE')
                    );
                break;
            default:
                return new FieldFilter(
                    $filterCondition->GetValue(0),
                    $this->GetConditionOperatorName($filterCondition->GetOperator()));
        }
    }

    private function GetConditionOperatorName($operator) {
        switch ($operator) {
            case FilterConditionOperator::Equals:
                return '=';
                break;
            case FilterConditionOperator::DoesNotEqual:
                return '<>';
                break;
            case FilterConditionOperator::IsGreaterThan:
                return '>';
                break;
            case FilterConditionOperator::IsGreaterThanOrEqualTo:
                return '>=';
                break;
            case FilterConditionOperator::IsLessThan:
                return '<=';
                break;
            case FilterConditionOperator::IsLessThanOrEqualTo:
                return '<=';
                break;
            case FilterConditionOperator::IsBetween:
                throw new Exception('');
                break;
            case FilterConditionOperator::IsNotBetween:
                throw new Exception('');
                break;
            case FilterConditionOperator::IsLike:
                return 'LIKE';
                break;
        }
        return '=';
    }

    private function GetFieldValueAsSQL($fieldName, $value) {
        $fieldType = $this->fields[$fieldName];
        return $this->commandImp->GetFieldValueAsSQL(new FieldInfo('', $fieldName, $fieldType, ''), $value);
    }

    private function GetGroupOperatorAsSQL($groupOperator) {
        switch ($groupOperator) {
            case FilterGroupOperator::LogicAnd:
                return 'AND';
            break;
            case FilterGroupOperator::LogicOr:
                return 'OR';
            break;
            case FilterGroupOperator::LogicXor:
                return 'XOR';
            break;
        }
        return '';
    }

    private function GetFilterConditionAsSQL(Dataset $dataset, FilterCondition $filter) {
        if ($filter->GetValue(0) == '' && $filter->GetDisplayValue() != '') {

            $newFilter = new FilterCondition($dataset->IsLookupFieldByPrimaryName($filter->GetFieldName()),
                $filter->GetOperator(), $filter->GetDisplayValue()
            );
            $legacyFilter = $this->CreateLegacyFilter($newFilter);
            return $this->legacyGenerator->CreateCondition($legacyFilter,
                $dataset->GetFieldInfoByName($newFilter->GetFieldName())
            );
        }
        else {
            $legacyFilter = $this->CreateLegacyFilter($filter);
            return $this->legacyGenerator->CreateCondition($legacyFilter,
                $dataset->GetFieldInfoByName($filter->GetFieldName())
            );
        }
        /*return StringUtils::Format('(%s %s %s)',
            $filter->GetFieldName(),
            $this->GetConditionOperatorAsSQL($filter->GetOperator()),
            $this->GetFieldValueAsSQL($filter->GetFieldName(), $filter->GetValue(0))
        );*/
    }

    public final function Generate(Dataset $dataset, $filter) {
        if ($filter instanceof FilterCondition) {
            /** @var FilterCondition $filter  */
            return $this->GetFilterConditionAsSQL($dataset, $filter);
        }
        else if ($filter instanceof FilterGroup) {
            $result = '';
            /** @var FilterGroup $filter  */
            for ($i = 0; $i < $filter->GetItemCount(); $i++) {
                AddStr($result,
                    $this->Generate(
                        $dataset,
                        $filter->GetItem($i)),
                        ' ' . $this->GetGroupOperatorAsSQL($filter->GetOperator()) . ' ');
            }
            return '(' . $result . ')';
        }
        return null;
    }
}

class FilterGroupOperator {
    const LogicAnd = 1;
    const LogicOr = 2;
    const LogicXor = 3;
}

class FilterConditionOperator {
    const Equals = 1;
    const DoesNotEqual = 2;
    const IsGreaterThan = 3;
    const IsGreaterThanOrEqualTo = 4;
    const IsLessThan = 5;
    const IsLessThanOrEqualTo = 6;
    const IsBetween = 7;
    const IsNotBetween = 8;
    const Contains = 9;
    const DoesNotContain = 10;
    const BeginsWith = 11;
    const EndsWith = 12;
    const IsLike = 13;
    const IsNotLike = 14;
}

class Filter {

    public function __construct() {
        $this->root = new FilterGroup();
    }

    /**
     * @param string $json
     * @return void
     */
    public function LoadFromJson($json) {
        $this->root->LoadFromData(SystemUtils::FromJSON($json));
    }

    /**
     * @return FilterGroup
     */
    public function GetRoot() {
        return $this->root;
    }

    public function IsEmpty() {
        return $this->GetRoot()->IsEmpty();
    }

    public function AsJson() {
        return SystemUtils::ToJSON($this->GetRoot()->SaveToArray());
    }

    public function AsString(Captions $captions) {
        return $this->GetRoot()->AsString($captions);
    }
}

class FilterCondition {
    private $displayValue;

    public function __construct($fieldName = null, $operator = null, $value = null) {
        $this->fieldName = $fieldName ? $fieldName : '';
        $this->operator = $operator ? $operator : FilterConditionOperator::Equals;
        $this->values = $value ? array($value) : array();
    }

    public function GetFieldName() {
        return $this->fieldName;
    }

    public function GetOperator() {
        return $this->operator;
    }

    public function GetValues() {
        return $this->values;
    }

    public function GetValue($index) {
        return $this->values[$index];
    }

    public function GetDisplayValue() {
        return $this->displayValue;
    }

    public function SetOperator($value) {
        $this->operator = $value;
    }

    public function SetFieldName($value) {
        $this->fieldName = $value;
    }

    public function SetValue($value){
        $this->values = array($value);
    }

    public function LoadFromData($data) {
        $this->fieldName = $data->fieldName;
        $this->operator = $data->operator;
        $this->values = array();
        $this->displayValue = $data->displayValue;
        foreach($data->values as $valueData)
            $this->values[] = $valueData;
    }

    public function SaveToArray() {
        $result = array();
        $result['type'] = 2;
        $result['fieldName'] = $this->GetFieldName();
        $result['operator'] = $this->GetOperator();
        $result['displayValue'] = $this->GetDisplayValue();

        $values = $this->GetValues();
        for ($i= 0; $i < count($values); $i++) {
            $result['values'][] = $values[$i];
        }
        return $result;
    }

    private final function GetOperatorAsString(Captions $captions) {
        switch ($this->GetOperator()) {
            case FilterConditionOperator::Equals:
                return '=';
                break;
            case FilterConditionOperator::DoesNotEqual:
                return '<>';
                break;
            case FilterConditionOperator::IsGreaterThan:
                return '>';
                break;
            case FilterConditionOperator::IsGreaterThanOrEqualTo:
                return '>=';
                break;
            case FilterConditionOperator::IsLessThan:
                return '<';
                break;
            case FilterConditionOperator::IsLessThanOrEqualTo:
                return '<=';
                break;
            case FilterConditionOperator::IsBetween:
                return $captions->GetMessageString('FilterOperatorIsBetween');
                break;
            case FilterConditionOperator::IsNotBetween:
                return $captions->GetMessageString('FilterOperatorIsNotBetween');
                break;
            case FilterConditionOperator::Contains:
                return $captions->GetMessageString('FilterOperatorContains');
                break;
            case FilterConditionOperator::DoesNotContain:
                return $captions->GetMessageString('FilterOperatorDoesNotContain');
                break;
            case FilterConditionOperator::BeginsWith:
                return $captions->GetMessageString('FilterOperatorBeginsWith');
                break;
            case FilterConditionOperator::EndsWith:
                return $captions->GetMessageString('FilterOperatorEndsWith');
                break;
            case FilterConditionOperator::IsLike:
                return $captions->GetMessageString('FilterOperatorIsLike');
                break;
            case FilterConditionOperator::IsNotLike:
                return $captions->GetMessageString('FilterOperatorIsNotLike');
                break;
        }
        return '';
    }

    public function IsEmpty() {
        return false;
    }

    public final function AsString(Captions $captions) {
        return $this->GetFieldName() . ' ' . $this->GetOperatorAsString($captions) . ' ' . $this->displayValue;
    }
}

class FilterGroup {

    /** @var int */
    private $operator;

    /** @var FilterGroup[]|FilterCondition[] */
    private $items;

    /**
     * @param int|null $operator
     */
    public function __construct($operator = null) {
        $this->operator = $operator ? $operator : FilterGroupOperator::LogicAnd;
        $this->items = array();
    }

    public function GetOperator() {
        return $this->operator;
    }

    /**
     * @param FilterGroup|FilterCondition $item
     */
    public function AddItem($item) {
        $this->items[] = $item;
    }

    /**
     * @param mixed $data
     */
    public function LoadFromData($data) {
        $this->items = array();

        $this->operator = intval($data->operator);
        foreach ($data->items as $itemData) {
            $item = null;
            if (intval($itemData->type) == 2) {
                $item = new FilterCondition();
            }
            else if (intval($itemData->type) == 1) {
                $item = new FilterGroup();
            }
            $item->LoadFromData($itemData);
            $this->AddItem($item);
        }
    }

    /**
     * @return array
     */
    public function SaveToArray() {

        $itemsData = array();
        $items = $this->GetItems();
        for ($i = 0; $i < count($items); $i++) {
            $itemsData[] = $items[$i]->SaveToArray();
        }

        return array(
            'type' => 1,
            'operator' => $this->GetOperator(),
            'items' => $itemsData
        );
    }

    /**
     * @return FilterGroup[]|FilterCondition[]
     */
    public function GetItems() {
        return $this->items;
    }

    /**
     * @return int
     */
    public function GetItemCount() {
        return count($this->items);
    }

    /**
     * @param int $index
     * @return FilterCondition|FilterGroup
     */
    public function GetItem($index) {
        return $this->items[$index];
    }

    /**
     * @param Captions $captions
     * @return string
     */
    public final function GetOperatorAsString(Captions $captions) {
        switch ($this->GetOperator()) {
            case FilterGroupOperator::LogicAnd:
                return 'And';
                break;
            case FilterGroupOperator::LogicOr:
                return 'Or';
                break;
            case FilterGroupOperator::LogicXor:
                return 'Xor';
                break;
        }
        return '';
    }

    /**
     * @param Captions $captions
     * @return string
     */
    public function AsString(Captions $captions) {
        $result = '';
        $items = $this->GetItems();
        for ($i = 0; $i < count($items); $i++) {
            if ($i > 0)
                $result .= ' ' . $this->GetOperatorAsString($captions) . ' ';

            if (count($items) > 1)
                $result .= '(';
            $result .= $items[$i]->AsString($captions);
            if (count($items) > 1)
                $result .= ')';
        }
        return $result;
    }

    /**
     * @param int $value
     */
    public function SetOperator($value) {
        $this->operator = $value;
    }

    public function IsEmpty() {
        $items = $this->GetItems();
        for ($i = 0; $i < count($items); $i++)
            if (!$items[$i]->IsEmpty())
                return false;
        return true;
    }
}

class FilterBuilderControl {
    /** @var \Grid */
    private $parentGrid;

    /** @var \Filter */
    private $filter;

    /** @var array[] */
    private $fields;

    /** @var \Captions */
    private $captions;

    /**
     * @param Grid $grid
     * @param Captions $captions
     */
    public function __construct(Grid $grid, Captions $captions) {
        $this->captions = $captions;
        $this->superGlobals = GetApplication()->GetSuperGlobals();
        $this->parentGrid = $grid;
        $this->filter = new Filter();
        $this->generator = new GenericFilterSQLGenerator(
            $this->parentGrid->GetDataset()->GetConnectionFactory()->CreateEngCommandImp()
        );
        $this->fields = array();
    }

    private function GetStorageProperty(){
        return 'filter_json';
    }

    public final function GetActiveFilterAsJson() {
        return $this->filter->AsJson();
    }

    public final function GetActiveFilterAsString() {
        return $this->filter->AsString($this->captions);
    }

    /**
     * @param $searchColumn
     * @param string $name
     * @param string $caption
     * @param int $fieldType
     * @param string  $editorClass
     * @param null|array $editorOptions
     */
    public final function AddField($searchColumn, $name, $caption, $fieldType, $editorClass, $editorOptions) {
        $this->generator->AddField($name, $fieldType);
        $this->fields[$name] = array(
            'searchColumn' => $searchColumn,
            'name' => $name,
            'caption' => $caption,
            'type' => $fieldType,
            'editorClass' => $editorClass,
            'editorOptions' => $editorOptions
        );
    }

    private function FieldTypeToJSFieldType($fieldType) {
        switch($fieldType) {
            case FieldType::Number:
                return 'Integer';
            case FieldType::String:
                return 'String';
            case FieldType::Blob:
                return 'Blob';
            case FieldType::DateTime:
                return 'DateTime';
            case FieldType::Date:
                return 'Date';
            case FieldType::Time:
                return 'Time';
            case FieldType::Boolean:
                return 'Boolean';

        }
        return 'String';
    }

    private function GetEditorClassByType($fieldType) {
        switch($fieldType) {
            case FieldType::Number:
                return 'TextEdit';
            case FieldType::String:
                return 'TextEdit';
            case FieldType::Blob:
                return 'TextEdit';
            case FieldType::DateTime:
                return 'DateTimeEdit';
            case FieldType::Date:
                return 'DateEdit';
            case FieldType::Time:
                return 'TextEdit';
            case FieldType::Boolean:
                return 'TextEdit';

        }
        return 'TextEdit';
    }

    public final function GetViewData() {
        $result = array();

        $fieldData = array();
        foreach($this->fields as $name => $data) {
            $fieldData[] = array(
                'Name' => $name,
                'Caption' => $data['caption'],
                'Type' => $this->FieldTypeToJSFieldType($data['type']),
                'EditorClass' =>
                    $data['editorClass'] ?
                        $data['editorClass'] :
                        $this->GetEditorClassByType($data['type']),
                'EditorOptions' => $data['editorOptions'] ?
                    SystemUtils::ToJSON($data['editorOptions']) : '{}'
            );
        }
        $result['Fields'] = $fieldData;

        return $result;
    }

    public final function ProcessMessages() {
        $storageProperty = $this->GetStorageProperty();
        if ($this->superGlobals->IsPostValueSet($storageProperty) || $this->superGlobals->IsSessionVariableSet($this->parentGrid->GetId() . $storageProperty)) {

            if ($this->superGlobals->IsPostValueSet($storageProperty))
                $filterJson = $this->superGlobals->GetPostValue($storageProperty);
            else
                $filterJson = $this->superGlobals->GetSessionVariable($this->parentGrid->GetId() . $storageProperty);

            $this->filter->LoadFromJson($filterJson);

            if (!$this->filter->IsEmpty())
                $this->parentGrid->GetDataset()->AddCustomCondition(
                    $this->generator->Generate(
                        $this->parentGrid->GetDataset(),
                        $this->filter->GetRoot()
                    )
                );
            $this->superGlobals->SetSessionVariable($this->parentGrid->GetId() . $storageProperty, $filterJson);
        }
        return $this->superGlobals->IsPostValueSet($storageProperty);
    }

    public function IsEmpty() {
        return $this->filter->IsEmpty();
    }
}